<?php

namespace App\Controller;

use App\Repository\MonsterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MonsterController extends AbstractController
{
    /**
     * @Route("/monsters/{slug}", name="app_monsters")
     */
    public function index (MonsterRepository $monsterRepository, string $slug): Response
    {
        $monster = $monsterRepository->findOneBy(['slug' => $slug]);

        return $this->render('monster/index.html.twig', [
            'monster' => $monster
        ]);
    }
}
