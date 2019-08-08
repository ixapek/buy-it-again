<?php


namespace ixapek\BuyItAgain\Entity;


class UserEntity extends AbstractEntity
{
    /** @var string $name */
    protected $name;

    /**
     * @inheritDoc
     * @return IEntity|UserEntity
     */
    public static function init(): IEntity
    {
        /** @var UserEntity $user */
        $user = parent::init();

        return $user
            ->setName('admin')
            ->setId(1);
    }

    /**
     * @inheritDoc
     */
    public function getFields(): array
    {
        return ['id', 'name'];
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return [
            'id'   => $this->getId(),
            'name' => $this->getName(),
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
     * @return UserEntity
     */
    public function setName(string $name): UserEntity
    {
        $this->name = $name;
        return $this;
    }
}