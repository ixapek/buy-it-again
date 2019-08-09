<?php


namespace ixapek\BuyItAgain\Component\Storage;


use ixapek\BuyItAgain\Component\Http\Code;
use ixapek\BuyItAgain\Component\Storage\Exception\StorageException;
use PDO;
use PDOException;

/**
 * Class PDOStorage
 *
 * @package ixapek\BuyItAgain
 */
class PDOStorage implements IStorage
{
    /** @var PDO $pdoInstance */
    protected $pdoInstance;
    /** @var bool $transaction Transaction started or not */
    protected $transaction = false;
    /**
     * PDOStorage constructor.
     *
     * @param array $config
     *
     * @throws StorageException
     */
    public function __construct(array $config)
    {
        try {
            $this->setPdoInstance(
                new PDO($config['dsn'] ?? '', $config['user'] ?? '', $config['password'] ?? '', [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                ])
            );
        } catch (PDOException $PDOException) {
            throw new StorageException("PDO connection error: " . $PDOException->getMessage(), Code::INTERNAL_ERROR, $PDOException);
        }
    }

    /**
     * @param array  $fields
     * @param string $entity
     * @param array  $condition
     * @param array  $sort
     * @param int    $limit
     *
     * @return array
     */
    public function select(array $fields, string $entity, array $condition = [], array $sort = [], int $limit = 0): array
    {
        $queryChunks = [
            "SELECT `" . implode('`,`', $fields) . "` FROM `$entity`",
        ];

        $execValues = [];

        if (false === empty($condition)) {
            $queryChunks[] = $this->makeWhere($condition, $execValues);
        }

        if (false === empty($sort)) {
            $queryChunks[] = $this->makeSort($sort);
        }

        if ($limit > 0) {
            $queryChunks[] = "LIMIT $limit";
        }

        $stmt = $this->getPdoInstance()->prepare(
            implode(' ', $queryChunks)
        );

        $stmt->execute($execValues);

        return $stmt->fetchAll();
    }

    /**
     * @param array $condition
     * @param array $execValues
     *
     * @return string
     */
    protected function makeWhere(array $condition, array &$execValues): string
    {
        $where = [];
        foreach ($condition as $field => $value) {
            if (true === is_array($value)) {
                $where[] = "`$field` IN (" . implode(',', array_fill(0, count($value), '?')) . ")";
                $execValues = array_merge($execValues, $value);
            } elseif (is_scalar($value)) {
                $where[] = "`$field` = ?";
                $execValues [] = $value;
            }
        }

        return (false === empty($where)) ?
            "WHERE " . implode(' AND ', $where) :
            "";
    }

    /**
     * @param array $sort
     *
     * @return string
     */
    protected function makeSort(array $sort): string
    {
        $sortArray = [];
        foreach ($sort as $field => $sortType) {
            $direction = strtoupper($sortType);
            if ($direction == "ASC" || $direction == "DESC") {
                $sortArray[] = "`" . $field . "` " . $direction;
            }
        }

        return (false === empty($sortArray)) ?
            "ORDER BY " . implode(',', $sortArray) :
            "";
    }

    /**
     * @return PDO
     */
    public function getPdoInstance(): PDO
    {
        return $this->pdoInstance;
    }

    /**
     * @param PDO $pdoInstance
     *
     * @return PDOStorage
     */
    public function setPdoInstance(PDO $pdoInstance): PDOStorage
    {
        $this->pdoInstance = $pdoInstance;
        return $this;
    }

    /**
     * @param array  $values
     * @param string $entity
     * @param array  $condition
     *
     * @return bool
     * @throws StorageException
     */
    public function update(array $values, string $entity, array $condition): bool
    {
        $queryChunks = [
            "UPDATE `$entity` SET",
        ];

        $queryChunks[] = $this->makeUpdateFields($values);

        $execValues = array_values($values);
        $queryChunks[] = $this->makeWhere($condition, $execValues);

        try {
            $stmt = $this->getPdoInstance()->prepare(
                implode(' ', $queryChunks)
            );

            return $stmt->execute($execValues);
        } catch (PDOException $PDOException){
            throw new StorageException("Storage update error: " . $PDOException->getMessage(), $PDOException->getCode(), $PDOException);
        }
    }

    /**
     * @param array  $values
     * @param string $entity
     *
     * @return int
     * @throws StorageException
     */
    public function insert(array $values, string $entity): int
    {
        $queryChunks = [
            "INSERT INTO `$entity`",
        ];

        $queryChunks[] = $this->makeInsertFields($values);

        $stmt = $this->getPdoInstance()->prepare(
            implode(' ', $queryChunks)
        );

        $execValues = array_values($values);
        try {
            $stmt->execute($execValues);

            return intval($this->getPdoInstance()->lastInsertId());
        } catch (PDOException $PDOException){
            throw new StorageException("Storage insert error: " . $PDOException->getMessage(), $PDOException->getCode(), $PDOException);
        }
    }

    /**
     * @param string $entity
     * @param array  $condition
     *
     * @return bool
     * @throws StorageException
     */
    public function delete(string $entity, array $condition): bool
    {
        $queryChunks = [
            "DELETE FROM `$entity`",
        ];

        $execValues = [];
        $queryChunks[] = $this->makeWhere($condition, $execValues);

        try {
            $stmt = $this->getPdoInstance()->prepare(
                implode(' ', $queryChunks)
            );

            return $stmt->execute($execValues);
        } catch (PDOException $PDOException){
            throw new StorageException("Storage delete error: " . $PDOException->getMessage(), $PDOException->getCode(), $PDOException);
        }
    }

    /**
     * Start transaction
     *
     * @throws StorageException
     */
    public function beginTransaction(): void
    {
        try {
            // If transaction already started don't try start new
            if( false === $this->isTransaction() ) {
                $this->setTransaction(
                    $this->getPdoInstance()->beginTransaction()
                );
            }
        } catch (PDOException $PDOException){
            throw new StorageException("Storage transaction start error: " . $PDOException->getMessage(), $PDOException->getCode(), $PDOException);
        }
    }

    /**
     * Persist transaction
     *
     * @throws StorageException
     */
    public function commit(): void
    {
        try {
            // Commit persist only for started transaction
            if( true === $this->isTransaction() ) {
                $this->setTransaction(
                    !$this->getPdoInstance()->commit()
                );
            }
        } catch (PDOException $PDOException){
            throw new StorageException("Storage transaction commit error: " . $PDOException->getMessage(), $PDOException->getCode(), $PDOException);
        }
    }

    /**
     * Rollback transaction
     *
     * @throws StorageException
     */
    public function rollback(): void
    {
        try {
            // Commit rollback only for started transaction
            if( true === $this->isTransaction() ) {
                $this->setTransaction(
                    !$this->getPdoInstance()->rollBack()
                );
            }
        } catch (PDOException $PDOException){
            throw new StorageException("Storage transaction rollback error: " . $PDOException->getMessage(), $PDOException->getCode(), $PDOException);
        }
    }

    /**
     * @return bool
     */
    public function isTransaction(): bool
    {
        return $this->transaction;
    }

    /**
     * @param bool $transaction
     *
     * @return PDOStorage
     */
    public function setTransaction(bool $transaction): PDOStorage
    {
        $this->transaction = $transaction;
        return $this;
    }

    /**
     * @param array $values
     *
     * @return string
     */
    protected function makeUpdateFields(array $values): string
    {
        $fields = [];
        foreach ($values as $field => $value) {
            $fields[] = "`$field`=?";
        }
        return implode(',', $fields);
    }

    /**
     * @param array $values
     *
     * @return string
     */
    protected function makeInsertFields(array $values)
    {
        $fieldsTable = [];
        $fieldsValues = [];
        foreach ($values as $field => $value) {
            $fieldsTable[] = "`$field`";
            $fieldsValues[] = "?";
        }
        return "(" . implode(',', $fieldsTable) . ") VALUES(" . implode(',', $fieldsValues) . ")";
    }
}