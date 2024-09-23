<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeControlleController extends AbstractController
{
    #[Route('/home/controlle', name: 'app_home_controlle')]
    public function index(): Response
    {
        return $this->render('home_controlle/index.html.twig', [
            'controller_name' => 'HomeControlleController',
        ]);
    }
}
