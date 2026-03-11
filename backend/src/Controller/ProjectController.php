<?php

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Service/ProjectService.php';

class ProjectController extends BaseController
{
    private ProjectService $projectService;

    public function __construct()
    {
        $this->projectService = new ProjectService();
    }

    /**
     * Return list of projects belonging to a specific client.
     */
    public function indexByClient(int $clientId): void
    {
        try {
            $projects = $this->projectService->getProjectsByClient($clientId);
            $this->jsonResponse($projects);
        } catch (InvalidArgumentException $e) {
            $this->errorResponse($e->getMessage(), 404);
        }
    }

    /**
     * Return all projects (including client data).
     */
    public function index(): void
    {
        $projects = $this->projectService->getAllProjects();
        $this->jsonResponse($projects);
    }

    /**
     * Create a new project from the request JSON body.
     */
    public function store(): void
    {
        $data = $this->getJsonInput();

        try {
            $this->projectService->createProject($data);

            $this->jsonResponse(['message' => 'Project created'], 201);

        } catch (InvalidArgumentException $e) {
            $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Return a single project by its ID.
     */
    public function show(int $id): void
    {
        try {
            $project = $this->projectService->getProjectById($id);
            $this->jsonResponse($project);
        } catch (InvalidArgumentException $e) {
            $this->errorResponse($e->getMessage(), 404);
        }
    }

    /**
     * Update a project by ID using the request JSON body.
     */
    public function update(int $id): void
    {
        $data = $this->getJsonInput();
        try {
            $this->projectService->updateProject($id, $data);
            $this->jsonResponse(['message' => 'Project updated']);
        } catch (InvalidArgumentException $e) {
            $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Delete a project by its ID.
     */
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