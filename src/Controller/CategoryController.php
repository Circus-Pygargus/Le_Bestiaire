<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/categories/{slug}", name="app_categories")
     */
    public function index (CategoryRepository $categoryRepository, string $slug='all'): Response
    {

        $categories = $categoryRepository->getValids();

        return $this->render('category/index.html.twig', [
            'categories' => $categories
        ]);
    }
}
