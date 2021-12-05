<?php

namespace App\Controller\Admin;

use App\Controller\Admin\AdminController;
use App\Entity\Monster;
use App\Form\Monster\MonsterFormType;
use App\Repository\MonsterRepository;
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
    public function list (MonsterRepository $monsterRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_CONTRIBUTOR');

        $monsters = [];

        $invalidMonsters = $monsterRepository->findBy(['featuredImage' => null]);

        if ($invalidMonsters) {
            $monsters['invalid'] = $invalidMonsters;
        }

        return $this->render('admin/monster/list.html.twig', [
            'monsters' => $monsters
        ]);
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
     * @Route("/edit/{slug}", name="edit")
     */
    public function edit (Request $request, MonsterRepository $monsterRepository, string $slug): Response
    {
        $this->denyAccessUnlessGranted('ROLE_CONTRIBUTOR');

        $monster = $monsterRepository->findOneBy(['slug' => $slug]);
        if (!$monster) {
            $this->addFlash('error', 'Le monstre <strong>' . $slug . '</strong> n\'existe pas');

            return $this->redirectToRoute('admin_monsters_list');
        }

        $form = $this->createForm(MonsterFormType::class, $monster);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->em->persist($monster);
                $this->em->flush();
            } catch (\Exception $e) {
                $this->logger->error($e->getMessage());
                $this->logger->debug($e->getTraceAsString());

                $this->addFlash('error', 'Un problème est survenu, le monstre n\'a pas été modifié !');
                return $this->redirectToRoute('admin_monsters_list');
            }

            $this->addFlash('success', 'Le monstre <strong>' . $monster->getName() . '</strong> a bien été modifié.');

            return $this->redirectToRoute('admin_monsters_list');
        }

        return $this->render('admin/monster/edit.html.twig', [
            'monsterForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete (MonsterRepository $monsterRepository, int $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        try {
            $monster = $monsterRepository->findOneBy(['id' => $id]);

            $this->em->remove($monster);
            $this->em->flush();
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            $this->logger->debug($e->getTraceAsString());

            $this->addFlash('error', 'Un problème est survenu, le monstre n\'a pas été supprimé !');
            return $this->redirectToRoute('admin_monsters_list');
        }

        $this->addFlash('success', 'Le monstre <strong>' . $monster->getName() . '</strong> vient d\'être supprimé');
        return $this->redirectToRoute('admin_monsters_list');
    }
}
