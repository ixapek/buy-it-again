<?php


namespace ixapek\BuyItAgain\Component\Main;

/**
 * Class Semaphore
 * @package ixapek\BuyItAgain\Component\Main
 */
class Semaphore
{
    use Singleton;

    /** @var int Semaphore ID prefix for order payment operation */
    public const ORDER_PAY = 104636;

    /** @var resource[] $sem Created semaphores */
    protected $sem = [];

    /**
     * Semaphore ON
     *
     * @param int $id Semaphore ID
     * @param int $acquire Max acquires
     * @return bool
     */
    public function acquire(int $id, int $acquire = 1): bool
    {
        return sem_acquire($this->getSem($id, $acquire));
    }

    /**
     * Release The Kraken!
     *
     * @param int $id Semaphore ID
     * @return bool
     */
    public function release(int $id): bool
    {
        return (true === $this->exists($id)) ?
            sem_release($this->sem[$id]) :
            true;
    }

    /**
     * Get semaphore by ID. If not exists, create for max acquires
     *
     * @param int $id Semaphore ID
     * @param int $acquire Max acquires
     * @return resource
     */
    protected function getSem(int $id, int $acquire)
    {
        if (false === $this->exists($id)) {
            $this->sem[$id] = sem_get($id, $acquire);
        }

        return $this->sem[$id];
    }

    /**
     * Check semaphore exists by ID
     *
     * @param int $id Semaphore ID
     * @return bool
     */
    protected function exists(int $id): bool
    {
        return array_key_exists($id, $this->sem);
    }
}