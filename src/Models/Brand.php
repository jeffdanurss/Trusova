<?php

class Brand
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function all(): array
    {
        $stmt = $this->db->query('SELECT * FROM brands ORDER BY name');
        return $stmt->fetchAll();
    }

    public function create(string $name): int
    {
        $stmt = $this->db->prepare('INSERT INTO brands (name) VALUES (:name) RETURNING id');
        $stmt->execute(compact('name'));
        return (int) $stmt->fetchColumn();
    }
}
