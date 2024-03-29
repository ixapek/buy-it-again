<?php


namespace ixapek\BuyItAgain\Service;

use ixapek\BuyItAgain\Component\Storage\{
    Exception\ConfigException,
    Exception\StorageException,
    Storage};
use ixapek\BuyItAgain\Entity\{
    IEntity,
    OrderEntity,
    ProductEntity};

/**
 * Class OrderService
 *
 * @package ixapek\BuyItAgain
 */
class OrderService extends AbstractService
{
    /**
     * Add a new order
     *
     * @param IEntity|OrderEntity $entity
     *
     * @throws ConfigException
     * @throws StorageException
     */
    protected function add(IEntity $entity): void
    {
        try {
            Storage::init()->beginTransaction();

            /** @var OrderEntity $entity */
            $newId = Storage::init()->insert(
                [
                    'user_id' => $entity->getUserId(),
                    'status'  => OrderEntity::STATUS_NEW,
                ],
                $entity->getEntityName()
            );
            $entity->setId($newId);


            foreach ($entity->getProducts() as $product) {
                /** @var ProductEntity $product */
                Storage::init()->insert(
                    [
                        'order_id'   => $entity->getId(),
                        'product_id' => $product->getId(),
                    ],
                    'order_product');
            }

            Storage::init()->commit();
        } catch (StorageException $e) {
            Storage::init()->rollback();

            throw $e;
        }
    }

    /**
     * Edit order
     *
     * @param IEntity|OrderEntity $entity
     *
     * @throws ConfigException
     * @throws StorageException
     */
    protected function edit(IEntity $entity): void
    {
        Storage::init()->update(
            [
                'user_id' => $entity->getUserId(),
                'status'  => $entity->getStatus(),
            ],
            $entity->getEntityName(),
            [
                'id' => $entity->getId(),
            ]
        );
    }
}