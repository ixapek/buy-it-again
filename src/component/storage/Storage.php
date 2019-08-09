<?php


namespace ixapek\BuyItAgain\Component\Storage;


use ixapek\BuyItAgain\Component\Main\Multiton;
use ixapek\BuyItAgain\Component\Storage\Exception\ConfigException;
use ixapek\BuyItAgain\Config;

/**
 * Class Storage
 *
 * @package ixapek\BuyItAgain
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
        if ($storage === null && true === defined(Config::class . '::STORAGE_DEFAULT')) {
            $storage = Config::STORAGE_DEFAULT;
        }

        if (false === is_string($storage)) {
            throw new ConfigException("Storage name bad syntax");
        }

        if (false === isset(static::$instance[$storage])) {
            if( false === defined(Config::class . '::STORAGE_CONFIG') ){
                throw new ConfigException("Storage configs not exists");
            }

            $storageConfigs = Config::STORAGE_CONFIG;
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