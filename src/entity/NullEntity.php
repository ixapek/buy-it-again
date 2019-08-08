<?php


namespace ixapek\BuyItAgain\Entity;

/**
 * Class NullEntity
 *
 * @package ixapek\BuyItAgain
 */
class NullEntity extends AbstractEntity
{
    /**
     * @inheritDoc
     */
    public function getFields(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return [];
    }
}