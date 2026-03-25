<?php

namespace Frontend\Controller;

use Frontend\Core\View;
use Frontend\Service\GithubService;

class HomeController
{
    private GithubService $githubService;

    public function __construct()
    {
        $this->githubService = new GithubService();
    }

    public function index()
    {
        $user = $this->githubService->getUser('AlmudenaRevuelto');

        return View::render('home/index.twig', [
            'user' => $user
        ]);
    }
}