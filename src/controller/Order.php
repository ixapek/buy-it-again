<?php


namespace ixapek\BuyItAgain\Controller;


use ixapek\BuyItAgain\Component\Http\{
    Exception\BadRequestException,
    Exception\InternalErrorException,
    Exception\NotFoundException,
    Method,
    Request};
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
     */
    public function post()
    {
        $request = Request::current();

        $productIds = $request->getArray('products');

        $orderService = new OrderService();

        $products = ProductRepository::init()->getBy(['id' => $productIds]);

        /** @var OrderEntity $order */
        $order = OrderEntity::init();
        $order->setProducts($products);

        $orderService->persist($order);
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
    public function put()
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

        if( false === $this->checkRequest() ){
            throw new InternalErrorException("Check request non-successful");
        }

        $orderService = new OrderService();
        $orderService->persist(
            $order->setStatus(OrderEntity::STATUS_PAY)
        );
    }

    /**
     * Send request and check success status
     *
     * @return bool
     */
    protected function checkRequest():bool{
        $checkResponse = Request::create('ya.ru')->send();

        return ($checkResponse->getCode() === 200);
    }
}