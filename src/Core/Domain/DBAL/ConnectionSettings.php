<?php declare(strict_types=1);

namespace Ads\Core\Domain\DBAL;

/**
 * Class ConnectionSettings
 * Класс для хранения настроек подключения к базе данных
 * @package Ads\Core\Domain\DBAL
 */
class ConnectionSettings
{
    private string $password = '';
    private string $user = '';
    private string $database = '';
    private string $host = '';
    private string $port = '';

    /**
     * ConnectionSettings constructor.
     * @param string $password
     * @param string $user
     * @param string $database
     * @param string $host
     * @param string $port
     */
    public function __construct(
        string $password,
        string $user,
        string $database,
        string $host,
        string $port
    )
    {
        $this->password = $password;
        $this->user = $user;
        $this->database = $database;
        $this->host = $host;
        $this->port = $port;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getUser(): string
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getDatabase(): string
    {
        return $this->database;
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @return string
     */
    public function getPort(): string
    {
        return $this->port;
    }
}