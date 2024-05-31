<?php

namespace App\Controller\Backend;

use App\Entity\Marque;
use App\Form\MarqueType;
use App\Repository\MarqueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/marques', name: 'admin.marques')]
class MarqueController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {
    }

    #[Route('', name: '.index', methods: ['GET'])]
    public function index(MarqueRepository $marqueRepo): Response
    {
        return $this->render('Backend/Marque/index.html.twig', [
            'marques' => $marqueRepo->findAll(),
        ]);
    }

    #[Route('/create', name: '.create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response|RedirectResponse
    {
        $marque = new Marque();

        $form = $this->createForm(MarqueType::class, $marque);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($marque);
            $this->em->flush();

            $this->addFlash('success', 'Marque crée avec succès');

            return $this->redirectToRoute('admin.marques.index');
        }

        return $this->render('Backend/Marque/create.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/{id}/edit', name: '.edit', methods: ['GET', 'POST'])]
    public function update(?Marque $marque, Request $request): Response|RedirectResponse
    {
        if (!$marque) {
            $this->addFlash('error', 'Model Not Found');

            return $this->redirectToRoute('admin.models.index');
        }

        $form = $this->createForm(MarqueType::class, $marque);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($marque);
            $this->em->flush();

            $this->addFlash('success', 'Marque modifié avec succès');

            return $this->redirectToRoute('admin.marques.index');
        }

        return $this->render('Backend/Marque/edit.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/{id}/delete', name: '.delete', methods: ['POST'])]
    public function delete(?Marque $marque, Request $request): Response|RedirectResponse
    {
        if (!$marque) {
            $this->addFlash('error', 'Model Not Found');

            return $this->redirectToRoute('admin.models.index');
        }

        if ($this->isCsrfTokenValid('delete' . $marque->getId(), $request->request->get('token'))) {
            $this->em->remove($marque);
            $this->em->flush();

            $this->addFlash('success', 'Marque supprimer avec succes');
        } else {
            $this->addFlash('error', 'Invalide token CSRF');
        }

        return $this->redirectToRoute('admin.marques.index');
    }
}
