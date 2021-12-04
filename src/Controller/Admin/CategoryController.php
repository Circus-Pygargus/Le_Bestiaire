<?php

namespace App\Controller\Admin;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CategoryController
 * @package App\Controller\Admin
 * @Route("/admin/Categories", name="admin_categories_")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/list", name="list")
     */
    public function list (CategoryRepository $categoryRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_CONTRIBUTOR');

        $categories = [];

        $invalidCategories = $categoryRepository->findBy(['featuredImage' => null]);

        if ($invalidCategories) {
            $categories['invalid'] = $invalidCategories;
        }

        return $this->render('admin/category/list.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/create", name="create")
     */
    public function create (): Response
    {
        $this->denyAccessUnlessGranted('ROLE_CONTRIBUTOR');

        return $this->render('admin/category/create.html.twig');
    }

    /**
     * @Route("/edit", name="edit")
     */
    public function edit (): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('admin/category/edit.html.twig');
    }
}
