<?php


namespace ixapek\BuyItAgain\Service;


use ixapek\BuyItAgain\Component\Storage\Exception\NotFoundException;
use ixapek\BuyItAgain\Component\Storage\Exception\StorageException;
use ixapek\BuyItAgain\Entity\IEntity;

interface IService
{
    /**
     * Save entity to storage. If entity ID is null, then add new record, else update exists
     *
     * @param IEntity $entity Entity object
     *
     * @throws StorageException
     * @throws NotFoundException
     */
    public function persist(IEntity $entity): void;
}