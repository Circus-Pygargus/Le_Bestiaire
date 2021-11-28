<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/categories/{slug}", name="app_categories")
     */
    public function index (string $slug='all'): Response
    {
        return $this->render('category/index.html.twig');
    }
}
