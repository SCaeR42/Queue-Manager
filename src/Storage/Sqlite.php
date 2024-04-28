<?php

namespace Scody\QueueManager\Storage;

use PDO;

class Sqlite extends PdoBase
{

    public function __construct(array $paramDB)
    {
        parent::__construct($paramDB);

        $dsn = $this->paramDB['sheme'] . ':' . $this->paramDB['db'];
        $this->createDatabaseIfNotExists();

        $this->db = new PDO($dsn);
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Проверяем существование базы данных и создаем ее при отсутствии
        $this->createDatabaseIfNotExists();
        $this->createTableIfNotExists();

        return $this->db;
    }

    private function createDatabaseIfNotExists(): void
    {
        // Проверяем существует ли база данных
        if ($this->paramDB['db'] !== ':memory:')
        {
            // создать файл DB если не найден
            if (!is_file($this->paramDB['db']))
            {
                if (file_put_contents($this->paramDB['db'], null) === false)
                {
                    throw new Exception('Error create DB');
                }
            }
        }
    }

    private function createTableIfNotExists(): void
    {
        $stmt   = $this->db->query("SELECT name FROM sqlite_master WHERE type='table' AND name='" . $this->paramDB['table'] . "'");
        $result = $stmt->fetchAll(PDO::FETCH_COLUMN);

        if (empty($result))
        {
            // Создаем таблицу, если она не существует
            $sql = "
            CREATE TABLE {$this->paramDB['table']} (
                id INTEGER PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                code VARCHAR(255) NOT NULL,
                data text,
                status VARCHAR(255) DEFAULT 'pending',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                completed_at TIMESTAMP NULL,
                processing_at TIMESTAMP NULL
            )";
            $this->db->exec($sql);
        }
    }

    public function getNext(mixed $changeStatus = null): ?array
    {
        $stmt = $this->db->prepare('SELECT *, ROWID as id FROM ' . $this->paramDB['table'] . " WHERE status='pending' ORDER BY created_at ASC LIMIT 1");
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($changeStatus && $row)
        {
            $stmt = $this->db->prepare("UPDATE " . $this->paramDB['table'] . " SET status='processing', processing_at=date('now') WHERE id=:taskId");
            $stmt->bindParam(':taskId', $row['id']);
            $stmt->execute();
        }

        return $row;
    }

    public function setCompleted($id): bool
    {
        $stmt = $this->db->prepare('UPDATE ' . $this->paramDB['table'] . " SET status='completed', completed_at=date('now') WHERE id=:taskId");
        $stmt->bindParam(':taskId', $id);

        return $stmt->execute();
    }
}


