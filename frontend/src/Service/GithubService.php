<?php

namespace Frontend\Service;

class GithubService
{
    private string $baseUrl = 'https://api.github.com';

    public function getUser(string $username): array
    {
        $user = $this->request("/users/$username");

        return isset($user['login']) ? $user : [];
    }

    public function getRepositories(string $username): array
    {
        $repos = $this->request("/users/$username/repos?sort=updated&per_page=100");
        $excludedNames = ['AlmudenaRevuelto', 'developer-project-portfolio'];

        if (!is_array($repos)) {
            return [];
        }

        if (isset($repos['message'])) {
            return [];
        }

        $repos = array_filter($repos, static function (array $repo) use ($excludedNames): bool {
            $name = $repo['name'] ?? '';

            return !in_array($name, $excludedNames, true);
        });

        return array_map([$this, 'mapRepository'], $repos);
    }

    private function mapRepository(array $repo): array
    {
        $owner = $repo['owner']['login'] ?? '';
        $name = $repo['name'] ?? '';
        $primaryLanguage = $repo['language'] ?? null;
        $languages = ($owner !== '' && $name !== '')
            ? $this->getLanguages($owner, $name)
            : [];

        if (empty($languages) && $primaryLanguage) {
            $languages = [$primaryLanguage];
        }
        

        return [
            'name' => $name,
            'description' => $repo['description'] ?? null,
            'language' => $languages,
            'url' => $repo['html_url'] ?? '#',
            'stars' => $repo['stargazers_count'] ?? 0,
            'updated_at' => $repo['updated_at'] ?? null,
            'homepage' => $repo['homepage'] ?? null,
        ];
    }

    private function getLanguages(string $username, string $repoName): array
    {
        $languages = $this->request("/repos/$username/$repoName/languages");

        return array_keys($languages);
    }

    private function request(string $path): array
    {
        $url = $this->baseUrl . $path;
        $token = getenv('GITHUB_TOKEN') ?: '';

        $headers = [
            'User-Agent: DeveloperPortfolioApp',
            'Accept: application/vnd.github+json',
            'X-GitHub-Api-Version: 2022-11-28',
        ];

        if ($token !== '') {
            $headers[] = 'Authorization: Bearer ' . $token;
        }

        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => implode("\r\n", $headers),
                'timeout' => 10,
                'ignore_errors' => true,
            ]
        ]);

        $response = @file_get_contents($url, false, $context);

        if ($response === false) {
            return [];
        }

        $statusLine = $http_response_header[0] ?? '';
        if (preg_match('/\s(\d{3})\s/', $statusLine, $matches) === 1) {
            $statusCode = (int) $matches[1];
            if ($statusCode >= 400) {
                return [];
            }
        }

        $decoded = json_decode($response, true);

        return is_array($decoded) ? $decoded : [];
    }
}