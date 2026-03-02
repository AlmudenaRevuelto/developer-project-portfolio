<?php

require_once __DIR__ . '/../Services/ClientService.php';

class ClientController
{
    private ClientService $clientService;

    public function __construct()
    {
        $this->clientService = new ClientService();
    }

    public function list(): void
    {
        $clients = $this->clientService->getAllClients();
        $this->jsonResponse($clients);
    }

    public function show(int $id): void
    {
        $client = $this->clientService->getClientById($id);

        if (!$client) {
            $this->jsonResponse(['error' => 'Client not found'], 404);
            return;
        }

        $this->jsonResponse($client);
    }

    public function create(array $data): void
    {
        try {
            $this->clientService->createClient(
                $data['name'] ?? '',
                $data['email'] ?? null
            );

            $this->jsonResponse(['message' => 'Client created'], 201);

        } catch (InvalidArgumentException $e) {
            $this->jsonResponse(['error' => $e->getMessage()], 400);
        }
    }

    public function delete(int $id): void
    {
        $this->clientService->deleteClient($id);
        $this->jsonResponse(['message' => 'Client deleted']);
    }

    private function jsonResponse($data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}
