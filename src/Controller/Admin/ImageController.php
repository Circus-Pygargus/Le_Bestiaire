<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ImageController
 * @package App\Controller\Admin
 * @Route("/admin/images", name="admin_images_")
 */
class ImageController extends AbstractController
{
    /**
     * @Route("list", name="list")
     */
    public function list (): Response
    {
        $this->denyAccessUnlessGranted('ROLE_CONTRIBUTOR');

        return $this->render('admin/image/list.html.twig');
    }

    /**
     * @Route("/create", name="create")
     */
    public function create (): Response
    {
        $this->denyAccessUnlessGranted('ROLE_CONTRIBUTOR');

        return $this->render('admin/image/create.html.twig');
    }
}
