<?php

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Services/ProjectService.php';

class ProjectController extends BaseController
{
    private ProjectService $projectService;

    public function __construct()
    {
        $this->projectService = new ProjectService();
    }

    public function indexByClient(int $clientId): void
    {
        try {
            $projects = $this->projectService->getProjectsByClient($clientId);
            $this->jsonResponse($projects);
        } catch (InvalidArgumentException $e) {
            $this->errorResponse($e->getMessage(), 404);
        }
    }

    public function index(): void
    {
        $projects = $this->projectService->getAllProjects();
        $this->jsonResponse($projects);
    }

    public function store(array $data): void
    {
        try {
            $id = $this->projectService->createProject($data);
            $this->jsonResponse([
                'message' => 'Project created',
                'id' => $id
            ], 201);
        } catch (InvalidArgumentException $e) {
            $this->errorResponse($e->getMessage(), 400);
        }
    }

    public function show(int $id): void
    {
        try {
            $project = $this->projectService->getProjectById($id);
            $this->jsonResponse($project);
        } catch (InvalidArgumentException $e) {
            $this->errorResponse($e->getMessage(), 404);
        }
    }

    public function update(int $id, array $data): void
    {
        try {
            $this->projectService->updateProject($id, $data);
            $this->jsonResponse(['message' => 'Project updated']);
        } catch (InvalidArgumentException $e) {
            $this->errorResponse($e->getMessage(), 400);
        }
    }

    public function destroy(int $id): void
    {
        try {
            $this->projectService->deleteProject($id);
            $this->jsonResponse(['message' => 'Project deleted']);
        } catch (InvalidArgumentException $e) {
            $this->errorResponse($e->getMessage(), 404);
        }
    }
}