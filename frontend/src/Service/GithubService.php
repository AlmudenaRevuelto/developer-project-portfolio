<?php

namespace Frontend\Service;

class GithubService
{
    private string $baseUrl = 'https://api.github.com';

    public function getUser(string $username): array
    {
        $url = $this->baseUrl . "/users/$username";

        $context = stream_context_create([
            "http" => [
                "header" => "User-Agent: PHP"
            ]
        ]);

        $response = @file_get_contents($url, false, $context);

        if ($response === false) {
            return [];
        }

        return json_decode($response, true) ?? [];
    }

    public function getRepositories(string $username): array
    {
        $url = $this->baseUrl . "/users/$username/repos";

        $context = stream_context_create([
            "http" => [
                "header" => "User-Agent: PHP"
            ]
        ]);

        $response = @file_get_contents($url, false, $context);

        if ($response === false) {
            return [];
        }

        $repos = json_decode($response, true);

        if (!is_array($repos)) {
            return [];
        }

        return array_map([$this, 'mapRepository'], $repos);
    }

    private function mapRepository(array $repo): array
    {
        return [
            'name' => $repo['name'],
            'description' => $repo['description'],
            'language' => $repo['language'],
            'url' => $repo['html_url'],
            'stars' => $repo['stargazers_count'],
            'updated_at' => $repo['updated_at'],
        ];
    }
}