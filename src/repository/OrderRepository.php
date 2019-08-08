<?php


namespace ixapek\BuyItAgain\Repository;


use ixapek\BuyItAgain\Component\Storage\Exception\ConfigException;
use ixapek\BuyItAgain\Component\Storage\Storage;
use ixapek\BuyItAgain\Entity\IEntity;
use ixapek\BuyItAgain\Entity\OrderEntity;
use ixapek\BuyItAgain\Entity\UserEntity;

/**
 * Class OrderRepository
 *
 * @package ixapek\BuyItAgain
 */
class OrderRepository extends AbstractRepository
{

    /**
     * @param OrderEntity $entity
     *
     * @return OrderEntity
     * @throws ConfigException
     */
    public function loadProducts(OrderEntity $entity): OrderEntity
    {

        $productRows = Storage::init()->select(['product_id'], 'order_product', ['order_id' => $entity->getId()]);

        $productEntities = ProductRepository::init()->getBy(['id' => array_column($productRows, 'product_id')]);

        return $entity->setProducts($productEntities);
    }

    /**
     * @param OrderEntity $entity
     *
     * @return OrderEntity
     */
    public function loadUser(OrderEntity $entity): OrderEntity
    {
        return $entity->setUser(UserEntity::init());
    }

    /**
     * We need create entity from array (DB row as usual)
     *
     * @param array $entityValues
     *
     * @return IEntity|OrderEntity
     */
    protected function mapFrom(array $entityValues): IEntity
    {
        $orderEntity = $this->getEntity();

        $this->addEntity(
            $orderEntity
                ->setUserId($entityValues['userId'])
                ->setId($entityValues['id'])
        );

        return $orderEntity;
    }

    /**
     * Get default entity object
     *
     * @return IEntity|OrderEntity
     */
    protected function getEntity(): IEntity
    {
        return OrderEntity::init();
    }
}