<?php


namespace ixapek\BuyItAgain\Repository;

use ixapek\BuyItAgain\Entity\{
    IEntity,
    ProductEntity};

/**
 * Class Product
 *
 * @package ixapek\BuyItAgain
 */
class ProductRepository extends AbstractRepository
{
    /**
     * @inheritDoc
     * @return ProductEntity
     */
    protected function mapFrom(array $entityValues): IEntity
    {
        $productEntity = $this->getEntity();

        $this->addEntity(
            $productEntity
                ->setName($entityValues['name'])
                ->setPrice($entityValues['price'])
                ->setId($entityValues['id'])
        );

        return $productEntity;
    }

    /**
     * @inheritDoc
     * @return ProductEntity
     */
    protected function getEntity(): IEntity
    {
        return ProductEntity::init();
    }
}