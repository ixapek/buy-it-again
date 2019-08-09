<?php


namespace ixapek\BuyItAgain\Repository;


use ixapek\BuyItAgain\Component\Storage\Exception\StorageException;
use ixapek\BuyItAgain\Entity\IEntity;

interface IRepository
{
    /**
     * Get one record by unique ID
     *
     * @param int $id Entity id
     *
     * @return IEntity
     * @throws StorageException
     */
    public function getOne(int $id): IEntity;

    /**
     * Get all entities
     *
     * @return IEntity[]
     * @throws StorageException
     */
    public function getAll(): array;

    /**
     * @param array $condition
     * @param array $sort
     * @param int   $limit
     *
     * @return IEntity[]
     * @throws StorageException
     */
    public function getBy(array $condition, array $sort = [], int $limit = 0): array;

}