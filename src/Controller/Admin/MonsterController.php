<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MonsterController
 * @package App\Controller\Admin
 * @Route("/admin/monsters", name="admin_monsters_")
 */
class MonsterController extends AbstractController
{
    /**
     * @Route("list", name="list")
     */
    public function list (): Response
    {
        $this->denyAccessUnlessGranted('ROLE_CONTRIBUTOR');

        return $this->render('admin/monster/list.html.twig');
    }

    /**
     * @Route("/create", name="create")
     */
    public function create (): Response
    {
        $this->denyAccessUnlessGranted('ROLE_CONTRIBUTOR');

        return $this->render('admin/monster/create.html.twig');
    }
}
