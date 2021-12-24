<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CategoryController
 * @package App\Controller
 * @Route("/categories", name="app_categories")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/", name="_list")
     */
    public function list (CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->getValids();

        return $this->render('category/list.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/{slug}", name="_show_one")
     */
    public function showOne (CategoryRepository $categoryRepository, string $slug): Response
    {
        $category = $categoryRepository->findOneBy(['slug' => $slug]);

        return $this->render('category/show-one.html.twig', [
            'category' => $category
        ]);
    }
}
