<?php

namespace App\Controller\Admin;

use App\Controller\Admin\AdminController;
use App\Entity\Monster;
use App\Form\Monster\MonsterFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MonsterController
 * @package App\Controller\Admin
 * @Route("/admin/monsters", name="admin_monsters_")
 */
class MonsterController extends AdminController
{
    /**
     * @Route("/list", name="list")
     */
    public function list (): Response
    {
        $this->denyAccessUnlessGranted('ROLE_CONTRIBUTOR');

        return $this->render('admin/monster/list.html.twig');
    }

    /**
     * @Route("/create", name="create")
     */
    public function create (Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_CONTRIBUTOR');

        $monster = new Monster();
        $form = $this->createForm(MonsterFormType::class, $monster);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->em->persist($monster);
                $this->em->flush();
            } catch (\Exception $e) {
                $this->logger->error($e->getMessage());
                $this->logger->debug($e->getTraceAsString());

                $this->addFlash('error', 'Un problème est survenu, le monstre n\'a pas été créé !');
                return $this->redirectToRoute('admin_monsters_list');
            }

            $this->addFlash('success', 'Le monstre <strong>' . $monster->getName() . '</strong> a bien été créé.');

            return $this->redirectToRoute('admin_monsters_list');
        }

        return $this->render('admin/monster/create.html.twig', [
            'monsterForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/edit", name="edit")
     */
    public function edit (): Response
    {
        $this->denyAccessUnlessGranted('ROLE_CONTRIBUTOR');

        return $this->render('admin/monster/edit.html.twig');
    }
}
