<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DefaultController extends AbstractController
{
    #[Route('/default/{id}', name: 'app_default')]
    public function index(int $id): Response
    {
        dd($id);
        return $this->render('index.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }
}
