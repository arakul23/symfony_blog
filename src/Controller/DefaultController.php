<?php

namespace App\Controller;

use App\Entity\Blog;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DefaultController extends AbstractController
{
    #[Route('/default/{id}', name: 'app_default', defaults: ['id' => 'index'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
      $blog = new Blog()->setTitle('test')->setText('test');

      $entityManager->persist($blog);
      $entityManager->flush($blog);
        return $this->render('index.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }
}
