<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class MainController extends AbstractController
{
    #[Route('/', name: 'default')]
    public function default(): Response
    {
        return $this->json([
            'message' => 'api-riot-2025',
        ]);
    }
}
