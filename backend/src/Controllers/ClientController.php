<?php

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Services/ClientService.php';

class ClientController extends BaseController
{
    private ClientService $clientService;

    public function __construct()
    {
        $this->clientService = new ClientService();
    }

    public function index(): void
    {
        $clients = $this->clientService->getAllClients();
        $this->jsonResponse($clients);
    }

    public function show(int $id): void
    {
        $client = $this->clientService->getClientById($id);

        if (!$client) {
            $this->errorResponse('Client not found', 404);
            return;
        }

        $this->jsonResponse($client);
    }

    public function store(array $data): void
    {
        try {
            $this->clientService->createClient(
                trim($data['name'] ?? ''),
                $data['email'] ?? null
            );

            $this->jsonResponse(['message' => 'Client created'], 201);

        } catch (InvalidArgumentException $e) {
            $this->errorResponse($e->getMessage(), 400);

        } catch (Throwable $e) {
            $this->errorResponse('Internal server error', 500);
        }
    }

    public function update(int $id, array $data): void
    {
        try {

            $updated = $this->clientService->updateClient(
                $id,
                $data['name'] ?? null,
                $data['email'] ?? null
            );

            if (!$updated) {
                $this->errorResponse('Client not found', 404);
                return;
            }

            $this->jsonResponse(['message' => 'Client updated']);

        } catch (InvalidArgumentException $e) {
            $this->errorResponse($e->getMessage(), 400);
        } catch (Throwable $e) {
            $this->errorResponse('Internal server error', 500);
        }
    }

    public function destroy(int $id): void
    {
        $deleted = $this->clientService->deleteClient($id);

        if (!$deleted) {
            $this->errorResponse('Client not found', 404);
            return;
        }

        $this->jsonResponse(['message' => 'Client deleted']);
    }

}
