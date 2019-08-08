<?php


namespace ixapek\BuyItAgain\Entity;


class ProductEntity extends AbstractEntity
{
    /** @var string $name Product name */
    protected $name;
    /** @var float $price Product price */
    protected $price;

    /**
     * @inheritDoc
     */
    public function getEntityName(): ?string
    {
        return 'product';
    }

    /**
     * @inheritDoc
     */
    public function getFields(): array
    {
        return [
            'id',
            'name',
            'price',
        ];
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return [
            'id'    => $this->getId(),
            'name'  => $this->getName(),
            'price' => $this->getPrice(),
        ];
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return ProductEntity
     */
    public function setName(string $name): ProductEntity
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @param float $price
     *
     * @return ProductEntity
     */
    public function setPrice(float $price): ProductEntity
    {
        $this->price = $price;
        return $this;
    }
}