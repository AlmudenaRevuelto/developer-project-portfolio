<?php

require_once __DIR__ . '/../Repositories/ClientRepository.php';
require_once __DIR__ . '/../Models/Client.php';

class ClientService
{
    private ClientRepository $clientRepository;

    public function __construct()
    {
        $this->clientRepository = new ClientRepository();
    }

    /**
     * @return Client[]
     */
    public function getAllClients(): array
    {
        return $this->clientRepository->findAll();
    }

    public function getClientById(int $id): ?Client
    {
        return $this->clientRepository->findById($id);
    }

    public function createClient(string $name, ?string $email): bool
    {
        if (empty($name)) {
            throw new InvalidArgumentException('Client name is required');
        }

        $client = new Client(null, $name, $email);
        return $this->clientRepository->create($client);
    }

    public function deleteClient(int $id): bool
    {
        return $this->clientRepository->delete($id);
    }

    public function updateClient(int $id, ?string $name, ?string $email): bool
    {
        if ($name !== null && trim($name) === '') {
            throw new InvalidArgumentException('Client name cannot be empty');
        }

        $existingClient = $this->clientRepository->findById($id);

        if (!$existingClient) {
            return false;
        }

        $updatedClient = new Client(
            $id,
            $name ?? $existingClient->getName(),
            $email ?? $existingClient->getEmail()
        );

        return $this->clientRepository->update($updatedClient);
    }
}
