<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Twig\Loader\FilesystemLoader;
use Twig\Environment;

// configurar Twig
$loader = new FilesystemLoader(__DIR__ . '/../templates');
$twig = new Environment($loader);

// renderizar template de prueba
echo $twig->render('test.twig', [
    'title' => 'Twig funcionando',
    'message' => 'Si ves esto, Twig funciona correctamente 🚀'
]);