<?php

namespace App\Controller\Admin;

use App\Controller\Admin\AdminController;
use App\Entity\Category;
use App\Form\Category\CreateCategoryFormType;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CategoryController
 * @package App\Controller\Admin
 * @Route("/admin/Categories", name="admin_categories_")
 */
class CategoryController extends AdminController
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
            $this->em->persist($category);
            $this->em->flush();

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

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete (CategoryRepository $categoryRepository, int $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        try {
            $category = $categoryRepository->findOneBy(['id' => $id]);

            $this->em->remove($category);
            $this->em->flush();

        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            $this->logger->debug($e->getTraceAsString());

            $this->addFlash('error', 'Un problème est survenu, la catégorie n\'a pas été supprimée !');
            return $this->redirectToRoute('admin_categories_list');
        }

        $this->addFlash('success', 'La catégorie <strong>' . $category->getName() . '</strong> vient d\'être supprimée');
        return $this->redirectToRoute('admin_categories_list');
    }
}
