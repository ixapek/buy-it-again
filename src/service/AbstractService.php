<?php


namespace ixapek\BuyItAgain\Service;


use ixapek\BuyItAgain\Component\Storage\Exception\{
    ConfigException,
    StorageException};
use ixapek\BuyItAgain\Component\Storage\Storage;
use ixapek\BuyItAgain\Entity\IEntity;
use ixapek\BuyItAgain\Entity\NullEntity;

/**
 * Class AbstractService
 *
 * @package ixapek\BuyItAgain
 */
abstract class AbstractService implements IService
{
    /**
     * @param IEntity $entity
     *
     * @throws ConfigException
     * @throws StorageException
     */
    public function persist(IEntity $entity): void
    {
        if (false === $this->isExists($entity)) {
            $this->add($entity);
        } else {
            $this->edit($entity);
        }
    }

    /**
     * Check Entity existence
     *
     * @param IEntity $entity
     *
     * @return bool
     * @throws StorageException
     */
    protected function isExists(IEntity $entity): bool
    {
        if (null === $entity->getId()) {
            return false;
        } else {
            return !($entity->getRepository()->getOne($entity->getId()) instanceof NullEntity);
        }
    }

    /**
     * Add new entity
     *
     * @param IEntity $entity
     *
     * @throws ConfigException
     * @throws StorageException
     */
    protected function add(IEntity $entity): void
    {
        $newId = Storage::init()->insert($entity->jsonSerialize(), $entity->getEntityName());
        $entity->setId($newId);
    }

    /**
     * Edit entity
     *
     * @param IEntity $entity
     *
     * @throws ConfigException
     * @throws StorageException
     */
    protected function edit(IEntity $entity): void
    {
        Storage::init()->update($entity->jsonSerialize(), $entity->getEntityName(), ['id' => $entity->getId()]);
    }
}