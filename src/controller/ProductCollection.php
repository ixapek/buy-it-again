<?php


namespace ixapek\BuyItAgain\Controller;

use ixapek\BuyItAgain\Component\Http\{
    Code,
    Method,
    Response};
use ixapek\BuyItAgain\Component\Main\RandomHelper;
use ixapek\BuyItAgain\Component\Storage\Exception\{
    ConfigException,
    StorageException};
use ixapek\BuyItAgain\Component\Storage\Storage;
use ixapek\BuyItAgain\Entity\ProductEntity;
use ixapek\BuyItAgain\Repository\ProductRepository;
use ixapek\BuyItAgain\Service\ProductService;

/**
 * Class ProductCollection
 *
 * @package ixapek\BuyItAgain
 */
class ProductCollection extends AbstractController
{

    /**
     * @inheritDoc
     */
    public function getAllowed(): array
    {
        return [Method::GET, Method::POST];
    }

    /**
     * Get all products
     *
     * @return Response
     * @throws ConfigException
     */
    public function get(): Response
    {
        return $this->response(
            ProductRepository::init()->getAll(),
            Code::OK
        );
    }

    /**
     * Generate 20 random products
     *
     * @return Response
     * @throws ConfigException
     * @throws StorageException
     */
    public function post(): Response
    {
        $productService = new ProductService();

        $generatedProducts = [];
        try {
            Storage::init()->beginTransaction();

            for ($i = 0; $i < 20; $i++) {
                /** @var ProductEntity $product */
                $product = ProductEntity::init();

                $productService->persist(
                    $product
                        ->setName(RandomHelper::word())
                        ->setPrice(RandomHelper::price())
                );

                $generatedProducts[] = $product;
            }

            Storage::init()->commit();
        } catch (StorageException $e){
            Storage::init()->rollback();
            throw $e;
        }

        return $this->response(
            $generatedProducts,
            Code::CREATED
        );
    }
}