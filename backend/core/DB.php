<?php

namespace core;

use PDO, PDOException;
use http\Exception;

class DB extends PDO
{
    public static ?self $instance;

    public function __construct()
    {
        $dsn = 'pgsql:host=' . $_ENV['DB_HOST'] . ';port=' . $_ENV['DB_PORT'] . ';dbname=' . $_ENV['DB_NAME'];

        try {
            parent::__construct($dsn, $_ENV['DB_USER'], $_ENV['DB_PASSWORD']);
            $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new Exception(
                $e->getMessage(),
                500
            );
        }
    }

    public static function getInstance(): self
    {
        if (!isset(self::$instance))
            self::$instance = new static();

        return self::$instance;
    }
}