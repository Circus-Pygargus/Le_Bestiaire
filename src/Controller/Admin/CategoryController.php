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
 * @Route("/admin/categories", name="admin_categories_")
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
     * @Route("/edit/{slug}", name="edit")
     */
    public function edit (Request $request, CategoryRepository $categoryRepository, string $slug): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $category = $categoryRepository->findOneBy(['slug' => $slug]);
        if (!$category) {
            $this->addFlash('error', 'La catégorie <strong>' . $slug . '</strong> n\'existe pas');

            return $this->redirectToRoute('admin_categories_list');
        }

        $form = $this->createForm(CreateCategoryFormType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->em->persist($category);
                $this->em->flush();
            } catch (\Exception $e) {
                $this->logger->error($e->getMessage());
                $this->logger->debug($e->getTraceAsString());

                $this->addFlash('error', 'Un problème est survenu, la catégorie n\'a pas été modifiée !');
                return $this->redirectToRoute('admin_categories_list');
            }

            $this->addFlash('success', 'La catégorie <strong>' . $category->getName() . '</strong> a bien été modifiée.');

            return $this->redirectToRoute('admin_categories_list');
        }

        return $this->render('admin/category/edit.html.twig', [
            'categoryForm' => $form->createView()
        ]);
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
