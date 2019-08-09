<?php


namespace ixapek\BuyItAgain\Repository;


use ixapek\BuyItAgain\Component\Storage\Exception\ConfigException;
use ixapek\BuyItAgain\Component\Storage\Storage;
use ixapek\BuyItAgain\Entity\{
    IEntity,
    NullEntity};

/**
 * Class AbstractRepository
 *
 * @package ixapek\BuyItAgain
 */
abstract class AbstractRepository implements IRepository
{

    /** @var IEntity[] $entities Entities collection */
    protected $entities = [];

    /**
     * Get one record by unique ID
     *
     * @param int $id Entity id
     *
     * @return IEntity
     * @throws ConfigException
     */
    public function getOne(int $id): IEntity
    {
        if (false === array_key_exists($id, $this->entities)) {
            $this->getBy(['id' => $id], [], 1);
        }

        return $this->entities[$id] ?? NullEntity::init();
    }

    /**
     * @param array $condition
     * @param array $sort
     * @param int   $limit
     *
     * @return IEntity[]
     * @throws ConfigException
     */
    public function getBy(array $condition, array $sort = [], int $limit = 0): array
    {
        $entityObject = $this->getEntity();

        $rows = Storage::init()->select(
            $entityObject->getFields(),
            $entityObject->getEntityName(),
            $condition
        );

        $result = [];
        foreach ($rows as $entityValues) {
            $entity = $this->mapFrom($entityValues);
            $this->addEntity($entity);

            $result[] = $entity;
        }

        return $result;
    }

    /**
     * Get default entity object
     *
     * @return IEntity
     */
    abstract protected function getEntity(): IEntity;

    /**
     * We need create entity from array (DB row as usual)
     *
     * @param array $row
     *
     * @return IEntity
     */
    abstract protected function mapFrom(array $row): IEntity;

    /**
     * @param IEntity $entity
     *
     * @return IRepository
     */
    protected function addEntity(IEntity $entity): IRepository
    {
        $this->entities[$entity->getId()] = $entity;

        return $this;
    }

    /**
     * Get all products entities
     *
     * @return IEntity[]
     * @throws ConfigException
     */
    public function getAll(): array
    {
        return $this->getBy([]);
    }
}