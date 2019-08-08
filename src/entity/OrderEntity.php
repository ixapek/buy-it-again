<?php


namespace ixapek\BuyItAgain\Entity;


use ixapek\BuyItAgain\Component\Storage\Exception\ConfigException;
use ixapek\BuyItAgain\Repository\OrderRepository;

/**
 * Class OrderEntity
 *
 * @package ixapek\BuyItAgain
 */
class OrderEntity extends AbstractEntity
{
    /** @var int New order */
    public const STATUS_NEW = 0;
    /** @var int Payed order */
    public const STATUS_PAY = 1;

    /** @var int $userId */
    protected $userId = 1;

    /** @var int $status */
    protected $status;

    /** @var UserEntity $user */
    protected $user;

    /** @var ProductEntity[] $products */
    protected $products;

    /**
     * Get fields (for DB request for example)
     *
     * @return array
     */
    public function getFields(): array
    {
        return ['id', 'user_id'];
    }

    /**
     * @inheritDoc
     * @return array
     * @throws ConfigException
     */
    public function jsonSerialize(): array
    {
        return [
            'id'       => $this->getId(),
            'user'     => $this->getUser(),
            'products' => $this->getProducts(),
        ];
    }

    /**
     * Get user entity who place this order (foreign)
     *
     * @return UserEntity
     */
    public function getUser(): UserEntity
    {
        if (null === $this->user) {
            OrderRepository::init()->loadUser($this);
        }
        return $this->user;
    }

    /**
     * @param UserEntity $user
     *
     * @return OrderEntity
     */
    public function setUser(UserEntity $user): OrderEntity
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Get products on this order (one-many)
     *
     * @return array
     * @throws ConfigException
     */
    public function getProducts(): array
    {
        if (null === $this->products) {
            OrderRepository::init()->loadProducts($this);
        }
        return $this->products;
    }

    /**
     * @param ProductEntity[] $products
     *
     * @return OrderEntity
     */
    public function setProducts(array $products): OrderEntity
    {
        $this->products = $products;
        return $this;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     *
     * @return OrderEntity
     */
    public function setUserId(int $userId): OrderEntity
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     *
     * @return OrderEntity
     */
    public function setStatus(int $status): OrderEntity
    {
        $this->status = $status;
        return $this;
    }
}