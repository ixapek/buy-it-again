<?php


namespace ixapek\BuyItAgain\Entity;

use ixapek\BuyItAgain\Repository\IRepository;
use JsonSerializable;

/**
 * Interface IEntity
 * All entity classes must implement this
 * Entity name for storage gets from class names or
 *
 * @package ixapek\BuyItAgain
 */
interface IEntity extends JsonSerializable
{
    /**
     * Make default current entity object
     *
     * @return IEntity
     */
    public static function init(): IEntity;

    /**
     * ID getter.
     * Each record is uniq
     *
     * @return int|null Use null if you want add new item to storage
     */
    public function getId(): ?int;

    /**
     * Id setter
     *
     * @param int $id
     *
     * @return IEntity
     */
    public function setId(int $id): IEntity;

    /**
     * If entity class name not equal name in storage (table or similar) this method must return
     * correct naming in storage
     *
     * @return string|null
     */
    public function getEntityName(): ?string;

    /**
     * Get fields (for DB request for example)
     *
     * @return array
     */
    public function getFields(): array;

    /**
     * Get repo object
     *
     * @return IRepository
     */
    public function getRepository(): IRepository;
}