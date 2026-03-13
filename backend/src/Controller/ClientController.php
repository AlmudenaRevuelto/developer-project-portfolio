<?php

namespace Backend\Controller;

use Backend\Service\ClientService;
use InvalidArgumentException;

class ClientController extends BaseController
{
    private ClientService $clientService;

    public function __construct()
    {
        $this->clientService = new ClientService();
    }

    /**
     * Return a list of all clients.
     */
    public function index(): void
    {
        $clients = $this->clientService->getAllClients();
        $this->jsonResponse($clients);
    }

    /**
     * Return a client by its ID.
     */
    public function show(int $id): void
    {
        $client = $this->clientService->getClientById($id);

        if (!$client) {
            $this->errorResponse('Client not found', 404);
            return;
        }

        $this->jsonResponse($client);
    }

    /**
     * Create a new client using JSON request body.
     */
    public function store(): void
    {
        $data = $this->getJsonInput();

        $this->clientService->createClient(
            trim($data['name'] ?? ''),
            $data['email'] ?? null
        );

        $this->jsonResponse(['message' => 'Client created'], 201);
    }

    /**
     * Update an existing client.
     */
    public function update(int $id): void
    {
        $data = $this->getJsonInput();
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

    /**
     * Delete a client by its ID.
     */
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
