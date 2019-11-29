<?php


namespace ixapek\BuyItAgain\Controller;

use ixapek\BuyItAgain\Component\Main\Semaphore;
use ixapek\BuyItAgain\Config;
use ixapek\BuyItAgain\Component\Http\{
    Code,
    Exception\BadRequestException,
    Exception\InternalErrorException,
    Exception\NotFoundException,
    Method,
    Request,
    Response};
use ixapek\BuyItAgain\Component\Storage\{
    Exception\ConfigException,
    Exception\StorageException};
use ixapek\BuyItAgain\Entity\{
    NullEntity,
    OrderEntity,
    ProductEntity};
use ixapek\BuyItAgain\Repository\{
    OrderRepository,
    ProductRepository};
use ixapek\BuyItAgain\Service\OrderService;

/**
 * Class Order
 *
 * @package ixapek\BuyItAgain
 */
class Order extends AbstractController
{
    /**
     * Get allowed methods for controller
     *
     * @return string[]
     */
    public function getAllowed(): array
    {
        return [Method::POST, Method::PUT];
    }

    /**
     * Place new order
     *
     * @throws ConfigException
     * @throws StorageException
     * @throws BadRequestException
     */
    public function post():Response
    {
        $request = Request::current();

        $productIds = $request->getIntArray('products');

        $orderService = new OrderService();
        $products = ProductRepository::init()->getBy(['id' => $productIds]);

        $diff = array_diff($productIds, array_keys($products));
        if( false === empty($diff) ){
            throw new BadRequestException("Products with id: ".implode(', ', $diff)." not exists");
        }

        /** @var OrderEntity $order */
        $order = OrderEntity::init();
        $order->setProducts($products);

        $orderService->persist($order);

        return $this->response(
            [$order],
            Code::CREATED
        );
    }

    /**
     * Pay order
     *
     * @throws BadRequestException
     * @throws ConfigException
     * @throws NotFoundException
     * @throws StorageException
     * @throws InternalErrorException
     */
    public function put():Response
    {
        $request = Request::current();

        $pay = $request->getFloat('pay');

        if (false === ($pay > 0)) {
            throw new BadRequestException("Pay sum must be greater than zero");
        }

        $orderId = $request->getInt('id');

        /** @var OrderEntity $order */
        $order = OrderRepository::init()->getOne($orderId);
        if (true === ($order instanceof NullEntity)) {
            throw new NotFoundException("Order not found");
        }

        if ($order->getStatus() > OrderEntity::STATUS_NEW) {
            throw new BadRequestException("Order already payed");
        }

        /** @var ProductEntity[] $products */
        $products = $order->getProducts();

        $productsSum = 0;
        foreach ($products as $product) {
            $productsSum += $product->getPrice();
        }

        if ($productsSum !== $pay) {
            throw new BadRequestException("Pay sum is incorrect");
        }

        // Semaphore lock for excluding double pay
        $semId = $this->getSemId($order);
        if( true === Semaphore::init()->acquire($semId) ) {

            $this->checkRequest();

            $orderService = new OrderService();
            $orderService->persist(
                $order->setStatus(OrderEntity::STATUS_PAY)
            );

            $response = $this->response(
                [$order],
                Code::OK
            );

            Semaphore::init()->release($semId);
        } else {
            $response = $this->response(
                [$order],
                Code::NOT_MODIFIED
            );
        }

        return $response;
    }

    /**
     * Send request and check success status
     *
     * @throws InternalErrorException
     */
    protected function checkRequest():void{

        $url = (defined(Config::class . '::CHECK_URL')) ? Config::CHECK_URL : 'ya.ru';

        $checkResponse = Request::create($url)
                                ->send();
        if($checkResponse->getCode() !== 200) {
            throw new InternalErrorException("Check request non-successful: code " . $checkResponse->getCode());
        }
    }

    /**
     * Get se
     *
     * @param OrderEntity $order
     * @return int
     */
    private function getSemId(OrderEntity $order):int{
        return intval(Semaphore::ORDER_PAY . $order->getId());
    }
}