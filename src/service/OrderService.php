<?php


namespace ixapek\BuyItAgain\Service;

use ixapek\BuyItAgain\Component\Storage\Exception\ConfigException;
use ixapek\BuyItAgain\Component\Storage\Exception\StorageException;
use ixapek\BuyItAgain\Component\Storage\Storage;
use ixapek\BuyItAgain\Entity\IEntity;
use ixapek\BuyItAgain\Entity\OrderEntity;
use ixapek\BuyItAgain\Entity\ProductEntity;

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
        Storage::init()->beginTransaction();
        try {
            /** @var OrderEntity $entity */
            $newId = Storage::init()->insert(
                [
                    'user_id' => $entity->getUserId(),
                    'status'  => OrderEntity::STATUS_NEW,
                ],
                $entity->getEntityName()
            );
            $entity->setId($newId);

            $orderProductsRows = [];
            foreach ($entity->getProducts() as $product) {
                /** @var ProductEntity $product */
                $orderProductsRows[] = [
                    'order_id'   => $entity->getId(),
                    'product_id' => $product->getId(),
                ];
            }

            Storage::init()->insert($orderProductsRows, 'order_products');

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