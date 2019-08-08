<?php


namespace ixapek\BuyItAgain\Component\Storage;


use ixapek\BuyItAgain\Component\Main\Multiton;
use ixapek\BuyItAgain\Component\Storage\Exception\ConfigException;

/**
 * Class Storage
 *
 * @package ixapek\BuyItAgain\Component\Storage
 */
class Storage
{
    use Multiton;

    /**
     * @param string|null $storage
     *
     * @return IStorage
     * @throws ConfigException
     */
    public static function init(string $storage = null): IStorage
    {
        if ($storage === null) {
            $storage = getenv('storage.default');
        }

        if (false === is_string($storage)) {
            throw new ConfigException("Storage name bad syntax");
        }

        if (false === isset(static::$instance[$storage])) {
            $storageConfigs = getenv('storage.config');
            if (false === $storageConfigs || false === isset($storageConfigs[$storage])) {
                throw new ConfigException("Storage $storage misconfiguration");
            }

            $storageConfig = $storageConfigs[$storage];
            if (false === isset($storageConfig['class']) || false === class_exists($storageConfig['class'])) {
                throw new ConfigException("Storage class for $storage not found");
            }

            if (true === isset($storageConfig['config']) && false === is_array($storageConfig['config'])) {
                throw new ConfigException("Storage config for $storage must be array");
            }

            if ($storageConfig['class'] instanceof IStorage) {
                throw new ConfigException("Storage class for $storage must implements IStorage");
            }

            static::$instance[$storage] = new $storageConfig['class']($storageConfig['config'] ?? []);
        }

        return static::$instance[$storage];
    }
}