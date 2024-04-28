<?php

namespace Scody\QueueManager\Storage;

use PDO;

class Mysql extends PdoBase
{

    public function __construct(array $paramDB)
    {
        parent::__construct($paramDB);

        $dsn = $this->paramDB['sheme'] . ':host=' . $this->paramDB['host'];
        // Подключаемся к MySQL без указания конкретной базы данных
        $this->db = new PDO($dsn, $this->paramDB['user'], $this->paramDB['password']);
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Проверяем существование базы данных и создаем ее при отсутствии
        $this->createDatabaseIfNotExists();

        // Подключаемся к указанной базе данных
        $dsn      .= ';dbname=' . $this->paramDB['db'];
        $this->db = new PDO($dsn, $this->paramDB['user'], $this->paramDB['password']);
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $this->createTableIfNotExists();
    }

    private function createDatabaseIfNotExists(): void
    {
        // Проверяем существует ли база данных
        $query = "SELECT 1 FROM information_schema.SCHEMATA WHERE SCHEMA_NAME = '" . $this->paramDB['db'] . "'";
        $stmt  = $this->db->query($query);
        if (!$stmt->fetch())
        {
            // Создаем базу данных, если она не существует
            $this->db->exec('CREATE DATABASE ' . $this->paramDB['db']);
        }
    }

    private function createTableIfNotExists(): void
    {
        $stmt = $this->db->query("SHOW TABLES LIKE '" . $this->paramDB['table'] . "'");
        if ($stmt->rowCount() == 0)
        {
            // Создаем таблицу, если она не существует
            $sql = "
            CREATE TABLE {$this->paramDB['table']} (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                code VARCHAR(255) NOT NULL,
                data text,
                status ENUM('pending', 'processing', 'completed') DEFAULT 'pending',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                completed_at TIMESTAMP NULL,
                processing_at TIMESTAMP NULL
            )";
            $this->db->exec($sql);
        }
    }
}


