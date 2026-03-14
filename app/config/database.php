<?php

declare(strict_types=1);

require_once __DIR__ . '/env.php';

final class Database
{
    private static ?\PDO $connection = null;
    private string $host;
    private string $dbName;
    private string $user;
    private string $pass;
    private string $charset;

    public function __construct(
        string $host = 'localhost',
        string $db = '',
        string $user = '',
        string $pass = '',
        string $charset = 'utf8mb4'
    ) {

        $this->host = $_ENV['DB_HOST'] ?? $host;
        $this->dbName = $_ENV['DB_NAME'] ?? $db;
        $this->user = $_ENV['DB_USER'] ?? $user;
        $this->pass = $_ENV['DB_PASS'] ?? $pass;
        $this->charset = $_ENV['DB_CHARSET'] ?? $charset;

    }

    public function getConnection(): \PDO
    {
        if (self::$connection instanceof \PDO) {
            return self::$connection;
        }

        try {

            $serverDsn = sprintf(
                'mysql:host=%s;charset=%s',
                $this->host,
                $this->charset
            );

            $serverConnection = new \PDO(
                $serverDsn,
                $this->user,
                $this->pass,
                $this->getOptions()
            );

            $dbNameSafe = preg_replace('/[^a-zA-Z0-9_]/', '', $this->dbName);

            if (!$dbNameSafe) {
                throw new \PDOException('Nome do banco de dados inválido.');
            }

            $serverConnection->exec(sprintf(
                'CREATE DATABASE IF NOT EXISTS `%s` CHARACTER SET %s COLLATE %s_unicode_ci',
                $dbNameSafe,
                $this->charset,
                $this->charset
            ));

            $databaseDsn = sprintf(
                'mysql:host=%s;dbname=%s;charset=%s',
                $this->host,
                $dbNameSafe,
                $this->charset
            );

            self::$connection = new \PDO(
                $databaseDsn,
                $this->user,
                $this->pass,
                $this->getOptions()
            );

            return self::$connection;

        } catch (\PDOException $e) {

            throw new \RuntimeException(
                'Falha ao conectar com o banco de dados.',
                0,
                $e
            );

        }
    }

    private function getOptions(): array
    {
        return [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES => false,
        ];
    }
}