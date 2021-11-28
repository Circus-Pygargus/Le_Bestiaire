<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ImageController extends AbstractController
{
    /**
     * @Route("/images/{slug}", name="app_images")
     */
    public function index (string $slug='all'): Response
    {
        return $this->render('image/index.html.twig');
    }
}
