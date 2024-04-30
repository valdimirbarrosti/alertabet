<?php

namespace App\Models;

class MonitorModel
{
    private $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        $query = "SELECT * FROM monitor";
        $stmt = $this->pdo->query($query);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getLiveEvents()
    {
        $query = "SELECT *
        FROM monitor
        WHERE time_start_event >= datetime('now', '-1 hour')
          AND time_start_event < datetime('now');";
        $stmt = $this->pdo->query($query);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
