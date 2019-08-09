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
            // TODO: Check config params
            $this->setPdoInstance(
                new PDO($config['dsn'], $config['user'], $config['password'], [
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
     */
    public function update(array $values, string $entity, array $condition): bool
    {
        $queryChunks = [
            "UPDATE `$entity` SET",
        ];

        $queryChunks[] = $this->makeUpdateFields($values);

        $execValues = array_values($values);
        $queryChunks[] = $this->makeWhere($condition, $execValues);

        $stmt = $this->getPdoInstance()->prepare(
            implode(' ', $queryChunks)
        );

        return $stmt->execute($execValues);
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
     * @param array  $values
     * @param string $entity
     *
     * @return int
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
        $stmt->execute($execValues);

        return intval($this->getPdoInstance()->lastInsertId());
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

    /**
     * @param string $entity
     * @param array  $condition
     *
     * @return bool
     */
    public function delete(string $entity, array $condition): bool
    {
        $queryChunks = [
            "DELETE FROM `$entity`",
        ];

        $execValues = [];
        $queryChunks[] = $this->makeWhere($condition, $execValues);

        $stmt = $this->getPdoInstance()->prepare(
            implode(' ', $queryChunks)
        );

        return $stmt->execute($execValues);
    }

    /**
     * Start transaction
     */
    public function beginTransaction(): void
    {
        $this->getPdoInstance()->beginTransaction();
    }

    /**
     * Persist transaction
     */
    public function commit(): void
    {
        $this->getPdoInstance()->commit();
    }

    /**
     * Rollback transaction
     */
    public function rollback(): void
    {
        $this->getPdoInstance()->rollBack();
    }
}