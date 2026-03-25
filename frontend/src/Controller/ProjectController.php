<?php

namespace Frontend\Controller;

use Frontend\Core\View;
use Frontend\Service\GithubService;

class ProjectController
{
    private GithubService $githubService;

    public function __construct()
    {
        $this->githubService = new GithubService();
    }

    public function index()
    {
        $projects = $this->githubService->getRepositories('AlmudenaRevuelto');

        return View::render('project/index.twig', [
            'projects' => $projects
        ]);
    }
}