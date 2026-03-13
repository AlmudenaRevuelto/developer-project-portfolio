<?php
use PHPUnit\Framework\TestCase;
use Backend\Service\ProjectService;
use Backend\Repository\ProjectRepository;
use Backend\Repository\ClientRepository;

class ProjectServiceTest extends TestCase
{
    public function testCreateProjectSuccess()
    {
        $projectRepositoryMock = $this->createMock(ProjectRepository::class);
        $clientRepositoryMock = $this->createMock(ClientRepository::class);

        $clientRepositoryMock
            ->method('findById')
            ->willReturn(new stdClass());

        $projectRepositoryMock
            ->method('create')
            ->willReturn(true);

        $service = new ProjectService(
            $projectRepositoryMock,
            $clientRepositoryMock
        );

        $data = [
            'name' => 'Portfolio',
            'client_id' => 1,
            'status' => 'active'
        ];

        $result = $service->createProject($data);

        $this->assertTrue($result);
    }
}