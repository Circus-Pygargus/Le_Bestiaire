<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MonsterController extends AbstractController
{
    /**
     * @Route("/monsters/{slug}", name="app_monsters")
     */
    public function index (string $slug='all'): Response
    {
        return $this->render('monster/index.html.twig');
    }
}
