<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MonsterController
 * @package App\Controller\Admin
 * @Route("/admin/movies", name="admin_movies_")
 */
class MovieController extends AbstractController
{
    /**
     * @Route("/list", name="list")
     */
    public function list (): Response
    {
        $this->denyAccessUnlessGranted('ROLE_CONTRIBUTOR');

        return $this->render('admin/movie/list.html.twig');
    }

    /**
     * @Route("/create", name="create")
     */
    public function create (): Response
    {
        $this->denyAccessUnlessGranted('ROLE_CONTRIBUTOR');

        return $this->render('admin/movie/create.html.twig');
    }

    /**
     * @Route("/edit", name="edit")
     */
    public function edit (): Response
    {
        $this->denyAccessUnlessGranted('ROLE_CONTRIBUTOR');

        return $this->render('admin/movie/edit.html.twig');
    }
}
