<?php declare(strict_types=1);
namespace Ads\Core\Domain\DBAL;

use Ads\Core\Domain\DBAL\Exception\DBALException;
use PDO;
use PDOException;

/**
 * Class DB
 * @package Ads\DB
 */
class DB
{
    private ?PDO $connect = null;
    private ConnectionSettings $connectionSettings;

    /**
     * DB constructor.
     * @param ConnectionSettings $connectionSettings
     * @throws DBALException
     */
    public function __construct(ConnectionSettings $connectionSettings)
    {
        try {
            $this->connectionSettings = $connectionSettings;
            $this->connect = new PDO(
                $this->makeDsn($this->connectionSettings),
                $connectionSettings->getUser(),
                $connectionSettings->getPassword(),
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        } catch (PDOException $ex) {
            throw new DBALException('Error while connect to database',0 , $ex);
        }
    }

    private function makeDsn(ConnectionSettings $connectionSettings): string
    {
        $host = $connectionSettings->getHost();
        $port = $connectionSettings->getPort();
        $dbname = $connectionSettings->getDatabase();
        return "pgsql:host={$host};port={$port};dbname={$dbname};";
    }

    /**
     * @throws DBALException
     */
    public function beginTransaction()
    {
        try {
            $this->connect->beginTransaction();
        } catch (PDOException $ex) {
            throw new DBALException('Error when try to begin transaction',0 , $ex);
        }
    }

    /**
     * @throws DBALException
     */
    public function commit()
    {
        try {
            $this->connect->commit();
        } catch (PDOException $ex) {
            throw new DBALException('Error when try to commit transaction',0 , $ex);
        }
    }

    /**
     * @throws DBALException
     */
    public function rollback()
    {
        try {
            $this->connect->rollBack();
        } catch (PDOException $ex) {
            throw new DBALException('Error when try to rollback transaction',0 , $ex);
        }
    }

    public function getPDO(): PDO
    {
        return $this->connect;
    }

    public function insertRow(string $table, array $data): int
    {
        try {
            $fields = array_keys($data);
            $values = array_values($data);
            $sql = "INSERT INTO {$table} {$this->placeInsertFields($fields)} VALUES {$this->placeInsertValues($values)}";
            $statement = $this->connect->prepare($sql);
            $statement->execute($values);
            return (int)$this->connect->lastInsertId();
        }  catch (PDOException $ex) {
            throw new DBALException('Error when try to insert row',0 , $ex);
        }
    }

    private function placeInsertFields(array $fields): string
    {
        return '(' . implode(', ', $fields) . ')';
    }

    private function placeInsertValues(array $values): string
    {
        return '(' . implode(', ', array_pad([], count($values), '?')) . ')';
    }

    public function selectById(string $table, string $field, int $id): array
    {
        try {
            $sql = "SELECT * FROM {$table} WHERE {$field} = ?";
            $statement = $this->connect->prepare($sql);
            $statement->execute([$id]);
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            if ($result) {
                return array_shift($result);
            }
            return [];
        } catch (PDOException $ex) {
            throw new DBALException(
                "Error when try to select row in table {$table} by id {$id} by field {$field}",
                0 ,
                $ex
            );
        }
    }

    public function updateById(string $table, string $field, int $id, array $data)
    {
        try {
            $fields = array_keys($data);
            $values = array_values($data);
            $values[] = $id;
            $sql = "UPDATE {$table} SET {$this->fieldsToSqlSet($fields)} WHERE {$field} = ?";
            $statement = $this->connect->prepare($sql);
            $statement->execute($values);
        }  catch (PDOException $ex) {
            throw new DBALException('Error when try to insert row',0 , $ex);
        }
    }

    private function fieldsToSqlSet(array $fields): string
    {
        $fieldsWithPalseholder = array_map(fn($elem) => $elem . ' = ?', $fields);
        return  implode(', ',$fieldsWithPalseholder);
    }

    public function select(string $sql): array
    {
        try {
            $statement = $this->connect->prepare($sql);
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $ex) {
            throw new DBALException('Error when try to run select query',0 , $ex);
        }
    }

    public function query(string $sql)
    {
        try {
            $this->connect->exec($sql);
        } catch (PDOException $ex) {
            throw new DBALException('Error when try to run query',0 , $ex);
        }
    }
}