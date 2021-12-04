<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\Category\CreateCategoryFormType;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function create (Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_CONTRIBUTOR');

        $category = new Category();
        $form = $this->createForm(CreateCategoryFormType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            $this->addFlash('success', 'La catégorie <strong>' . $category->getName() . '</strong> a bien été crée.');

            return $this->redirectToRoute('admin_categories_list');
        }

        return $this->render('admin/category/create.html.twig', [
            'categoryForm' => $form->createView()
        ]);
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
