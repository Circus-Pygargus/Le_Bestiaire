<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{
    /**
     * @Route("/movies/{slug}", name="app_movies")
     */
    public function index (string $slug='all'): Response
    {
        return $this->render('movie/index.html.twig');
    }
}
