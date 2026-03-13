<?php

namespace Frontend\Controller;

use Frontend\Service\ApiService;
use Frontend\Core\View;

class HomeController
{
    public function index()
    {
        $api = new ApiService();

        $projects = $api->getProjects();

        View::render('home/index.twig', [
            'projects' => $projects
        ]);
    }
}