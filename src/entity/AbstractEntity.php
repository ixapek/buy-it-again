<?php


namespace ixapek\BuyItAgain\Entity;

use Exception;
use ixapek\BuyItAgain\Repository\AbstractRepository;
use ixapek\BuyItAgain\Repository\IRepository;

/**
 * Class AbstractEntity
 *
 * @package ixapek\BuyItAgain
 */
abstract class AbstractEntity implements IEntity
{
    /** @var int|null $id Unique index */
    protected $id;

    /**
     * Make default current entity object
     *
     * @return IEntity
     */
    public static function init(): IEntity
    {
        return new static();
    }

    /**
     * ID getter.
     * Each record is uniq
     *
     * @return int|null Use null if you want add new item to storage
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return IEntity
     */
    public function setId(int $id): IEntity
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Method realised basic definitions of repositories.
     * Override this if used otherwise
     *
     * @return IRepository
     * @throws Exception
     */
    public function getRepository(): IRepository
    {
        /** @var AbstractRepository $repositoryDefaultClassname */
        $repositoryDefaultClassname = str_replace('Entity', 'Repository', static::class);

        if (true === class_exists($repositoryDefaultClassname) && method_exists($repositoryDefaultClassname, 'init')) {
            return $repositoryDefaultClassname::init();
        } else {
            throw new Exception("Repository for " . $this->getEntityName() . " not found");
        }
    }

    /**
     * By default, entity name in storage are equals entity class name
     *
     * @inheritDoc
     */
    public function getEntityName(): ?string
    {
        return null;
    }
}