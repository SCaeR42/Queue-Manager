<?php

namespace Scody\QueueManager\Storage;

use PDO;
use Scody\QueueManager\Interfaces\StorageInterface;

class PdoBase implements StorageInterface
{
    protected PDO $db;
    protected array $paramDB;

    public function __construct(array $paramDB)
    {
        $this->paramDB = $paramDB;
    }

    private function createDatabaseIfNotExists(): void
    {
    }

    private function createTableIfNotExists(): void
    {
    }

    public function add(string $code, string $dataJson, string $name = null): bool
    {
        $stmt = $this->db->prepare('INSERT INTO ' . $this->paramDB['table'] . ' (name, code, data) VALUES (:name, :code, :additionalData)');
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':code', $code);
        $stmt->bindParam(':additionalData', $dataJson);

        return $stmt->execute();
    }

    public function getNext(mixed $changeStatus = null): ?array
    {
        $this->db->beginTransaction();
        $stmt = $this->db->prepare("SELECT * FROM " . $this->paramDB['table'] . " WHERE status='pending' ORDER BY created_at ASC LIMIT 1 FOR UPDATE");
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($changeStatus && $row)
        {
            $stmt = $this->db->prepare("UPDATE " . $this->paramDB['table'] . " SET status='processing', processing_at=NOW() WHERE id=:taskId");
            $stmt->bindParam(':taskId', $row['id']);
            $stmt->execute();
        }

        $this->db->commit();

        return $row;
    }

    public function setCompleted($id): bool
    {
        $stmt = $this->db->prepare("UPDATE " . $this->paramDB['table'] . " SET status='completed', completed_at=NOW() WHERE id=:taskId");
        $stmt->bindParam(':taskId', $id);

        return $stmt->execute();
    }

}


