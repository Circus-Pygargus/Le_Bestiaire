<?php

namespace App\Controller\Admin;

use App\Controller\Admin\AdminController;
use App\Entity\Image;
use App\Form\Image\ImageFormType;
use App\Repository\ImageRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ImageController
 * @package App\Controller\Admin
 * @Route("/admin/images", name="admin_images_")
 */
class ImageController extends AdminController
{
    /**
     * @Route("list", name="list")
     */
    public function list (ImageRepository $imageRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_CONTRIBUTOR');

        $images = $imageRepository->findAll();

        return $this->render('admin/image/list.html.twig', [
            'images' => $images
        ]);
    }

    /**
     * @Route("/create", name="create")
     */
    public function create (Request $request, ImageRepository $imageRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_CONTRIBUTOR');

        $image = new Image();

        $form = $this->createForm(ImageFormType::class, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $image->setPostedBy($this->getUser());
                $this->em->persist($image);
                $this->em->flush();

                $this->addFlash('success', 'L\'image ' . $image->getName() . ' vient d\'être créée');

                return $this->redirectToRoute('admin_images_list');
            } catch (\Exception $e) {
                $this->logger->error($e->getMessage());
                $this->logger->debug($e->getTraceAsString());

                $this->addFlash('error', 'Un problème est survenu, le monstre n\'a pas été créé');
            }
        }

        return $this->render('admin/image/create.html.twig', [
            'imageForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/edit", name="edit")
     */
    public function edit (): Response
    {
        $this->denyAccessUnlessGranted('ROLE_CONTRIBUTOR');

        return $this->render('admin/image/edit.html.twig');
    }
}
