<?php

namespace Backend\Model;
use Backend\Model\Client;
use JsonSerializable;

class Project implements JsonSerializable
{
    private ?int $id;
    private string $name;
    private string $status;
    private ?string $createdAt;
    private ?Client $client;

    public function __construct(
        ?int $id,
        string $name,
        string $status,
        ?string $createdAt = null,
        ?Client $client = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->status = $status;
        $this->createdAt = $createdAt;
        $this->client = $client;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function jsonSerialize(): mixed
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'status' => $this->status,
            'created_at' => $this->createdAt,
        ];

        if ($this->client) {
            $data['client'] = $this->client->jsonSerialize();
        }

        return $data;
    }
}
