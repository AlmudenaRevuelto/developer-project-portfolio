<?php

namespace Backend\Repository;
use Backend\Config\Database;
use Backend\Model\Project;
use Backend\Model\Client;
use PDO;

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

    /**
     * Insert a new project record.
     *
     * @param Project $project
     * @return bool
     */
    public function create(Project $project): bool
    {
        $sql = "INSERT INTO projects (client_id, name, status)
                VALUES (:client_id, :name, :status)";

        $stmt = $this->connection->prepare($sql);

        $client = $project->getClient();
        if (!$client) {
            throw new InvalidArgumentException('Project must have a client');
        }

        return $stmt->execute([
            'client_id' => $client->getId(),
            'name' => $project->getName(),
            'status' => $project->getStatus()
        ]);
    }

    /**
     * Find a project by ID including its client data.
     *
     * @param int $id
     * @return Project|null
     */
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

    /**
     * Update project name and status.
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
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

    /**
     * Delete a project record.
     *
     * @param int $id
     * @return bool
     */
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