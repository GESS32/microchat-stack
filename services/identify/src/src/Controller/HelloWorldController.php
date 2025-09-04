<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelloWorldController extends AbstractController
{
    #[Route('/hello', name: 'main')]
    public function __invoke(): Response
    {
        return new Response('Hello World (Traefik -> FrankenPHP -> Symfony)');
    }
}
