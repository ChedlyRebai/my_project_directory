<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeeController extends AbstractController
{
    #[Route('/homee', name: 'app_homee')]
    public function index(): Response
    {
        return $this->render('homee/index.html.twig', [
            'controller_name' => 'HomeeController',
        ]);
    }
}
