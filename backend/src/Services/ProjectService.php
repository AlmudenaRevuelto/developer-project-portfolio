<?php

require_once __DIR__ . '/../Repositories/ProjectRepository.php';
require_once __DIR__ . '/../Repositories/ClientRepository.php';
require_once __DIR__ . '/../Models/Project.php';

class ProjectService
{
    private ProjectRepository $projectRepository;
    private ClientRepository $clientRepository;

    public function __construct()
    {
        $this->projectRepository = new ProjectRepository();
        $this->clientRepository = new ClientRepository();
    }

    public function getProjectsByClient(int $clientId): array
    {
        $client = $this->clientRepository->findById($clientId);

        if (!$client) {
            throw new InvalidArgumentException('Client not found');
        }

        return $this->projectRepository->findByClientId($clientId);
    }

    /**
     * @return Project[]
     */
    public function getAllProjects(): array
    {
        return $this->projectRepository->findAllWithClient();
    }

    public function createProject(array $data): int
    {
        if (empty($data['name'])) {
            throw new InvalidArgumentException('Project name is required');
        }

        if (empty($data['client_id'])) {
            throw new InvalidArgumentException('client_id is required');
        }

        $client = $this->clientRepository->findById($data['client_id']);

        if (!$client) {
            throw new InvalidArgumentException('Client not found');
        }

        return $this->projectRepository->create($data);
    }

    public function getProjectById(int $id): Project
    {
        $project = $this->projectRepository->findByIdWithClient($id);

        if (!$project) {
            throw new InvalidArgumentException('Project not found');
        }

        return $project;
    }

    public function updateProject(int $id, array $data): void
    {
        if (empty($data['name'])) {
            throw new InvalidArgumentException('Project name is required');
        }

        if (!isset($data['status'])) {
            throw new InvalidArgumentException('Project status is required');
        }

        $allowedStatus = ['active', 'finished'];

        if (!in_array($data['status'], $allowedStatus)) {
            throw new InvalidArgumentException('Invalid status value');
        }

        $updated = $this->projectRepository->update($id, $data);

        if (!$updated) {
            throw new InvalidArgumentException('Project not found');
        }
    }

    public function deleteProject(int $id): void
    {
        $deleted = $this->projectRepository->delete($id);

        if (!$deleted) {
            throw new InvalidArgumentException('Project not found');
        }
    }
}