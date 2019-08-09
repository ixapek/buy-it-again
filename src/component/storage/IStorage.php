<?php


namespace ixapek\BuyItAgain\Component\Storage;

use ixapek\BuyItAgain\Component\Storage\Exception\StorageException;

/**
 * Interface IStorage
 *
 * Notation for works with entities in storage
 *
 * @package ixapek\BuyItAgain
 */
interface IStorage
{
    /**
     * IStorage constructor.
     *
     * @param array $config
     */
    public function __construct(array $config);

    /**
     * @param array  $fields
     * @param string $entity
     * @param array  $condition
     * @param array  $sort
     * @param int    $limit
     *
     * @return array
     */
    public function select(array $fields, string $entity, array $condition = [], array $sort = [], int $limit = 0): array;

    /**
     * @param array  $values
     * @param string $entity
     * @param array  $condition
     *
     * @return bool
     * @throws StorageException
     */
    public function update(array $values, string $entity, array $condition): bool;

    /**
     * @param array  $values
     * @param string $entity
     *
     * @return int
     * @throws StorageException
     */
    public function insert(array $values, string $entity): int;

    /**
     * @param string $entity
     * @param array  $condition
     *
     * @return bool
     * @throws StorageException
     */
    public function delete(string $entity, array $condition): bool;

    /**
     * Start transaction
     * @throws StorageException
     */
    public function beginTransaction(): void;

    /**
     * Persist transaction
     * @throws StorageException
     */
    public function commit(): void;

    /**
     * Rollback transaction
     * @throws StorageException
     */
    public function rollback(): void;
}