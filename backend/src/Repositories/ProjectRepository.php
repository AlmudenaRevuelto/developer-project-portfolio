<?php

require_once __DIR__ . '/../Models/Project.php';
require_once __DIR__ . '/../Models/Client.php';
require_once __DIR__ . '/../../config/database.php';

class ProjectRepository
{
    private PDO $connection;

    public function __construct()
    {
        $database = new Database();
        $this->connection = $database->connect();
    }
    
    /**
     * @return Project[]
     */
    public function findByClientId(int $clientId): array
    {
        $query = "SELECT * FROM projects WHERE client_id = :client_id";

        $stmt = $this->connection->prepare($query);

        $stmt->execute([
            'client_id' => $clientId
        ]);

        $projects = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $projects[] = new Project(
                (int) $row['id'], 
                $row['name'], 
                $row['status'], 
                $row['created_at'] ?? null,
                null
            );
        }

        return $projects;
    }

    /**
     * @return Project[]
     */
    public function findAllWithClient(): array
    {
        $query = "SELECT p.id, p.name, p.status, p.created_at, c.id AS client_id, c.name AS client_name, c.email AS client_email 
        FROM projects p JOIN clients c ON p.client_id = c.id ORDER BY p.id";

        $stmt = $this->connection->query($query);

        $projects = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $client = new Client(
                (int) $row['client_id'], 
                $row['client_name'], 
                $row['client_email'] ?? null
            );

            $projects[] = new Project(
                (int) $row['id'], 
                $row['name'], 
                $row['status'], 
                $row['created_at'] ?? null,
                $client
            );
        }

        return $projects;
    }

    public function create(array $data): int
    {
        $query = "INSERT INTO projects (client_id, name, status) VALUES (:client_id, :name, :status)";

        $stmt = $this->connection->prepare($query);

        $stmt->execute([
            'client_id' => $data['client_id'],
            'name' => $data['name'],
            'status' => $data['status'] ?? 'active'
        ]);

        return (int)$this->connection->lastInsertId();
    }

    public function findByIdWithClient(int $id): ?Project
    {
        $query = "SELECT p.id, p.name, p.status, p.created_at, c.id AS client_id, c.name AS client_name, c.email AS client_email 
        FROM projects p JOIN clients c ON p.client_id = c.id WHERE p.id = :id LIMIT 1";

        $stmt = $this->connection->prepare($query);

        $stmt->execute([
            'id' => $id
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        $client = new Client(
            (int) $row['client_id'], 
            $row['client_name'], 
            $row['client_email'] ?? null
        );

        return new Project(
            (int) $row['id'], 
            $row['name'], 
            $row['status'], 
            $row['created_at'] ?? null,
            $client
        );
    }

    public function update(int $id, array $data): bool
    {
        $query = "UPDATE projects SET name = :name, status = :status WHERE id = :id";

        $stmt = $this->connection->prepare($query);

        $stmt->execute([
            'id' => $id,
            'name' => $data['name'],
            'status' => $data['status']
        ]);

        return $stmt->rowCount() > 0;
    }

    public function delete(int $id): bool
    {
        $query = "DELETE FROM projects WHERE id = :id";
        $stmt = $this->connection->prepare($query);

        $stmt->execute([
            'id' => $id
        ]);

        return $stmt->rowCount() > 0;
    }
}