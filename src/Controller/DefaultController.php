<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DefaultController extends AbstractController
{
    #[Route('/default/{id}', name: 'app_default', defaults: ['id' => 'index'])]
    public function index(): Response
    {
      return $this->redirectToRoute('app_blog_index');
    }
}
