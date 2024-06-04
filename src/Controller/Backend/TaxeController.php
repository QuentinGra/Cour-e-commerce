<?php

namespace App\Controller\Backend;

use App\Entity\Taxe;
use App\Form\TaxeType;
use App\Repository\TaxeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/taxe', name: 'admin.taxe')]
class TaxeController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {
    }

    #[Route('', name: '.index', methods: ['GET'])]
    public function index(TaxeRepository $taxeRepo): Response
    {
        return $this->render('Backend/Taxe/index.html.twig', [
            'taxes' => $taxeRepo->findAll(),
        ]);
    }

    #[Route('/create', name: '.create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response|RedirectResponse
    {
        $taxe = new Taxe();

        $form = $this->createForm(TaxeType::class, $taxe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($taxe);
            $this->em->flush();

            $this->addFlash('success', 'Taxe crée avec succès');

            return $this->redirectToRoute('admin.taxe.index');
        }

        return $this->render('Backend/Taxe/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: '.edit', methods: ['GET', 'POST'])]
    public function update(?Taxe $taxe, Request $request): Response|RedirectResponse
    {
        if (!$taxe) {
            $this->addFlash('error', 'Taxe Not Found');

            return $this->redirectToRoute('admin.taxe.index');
        }

        $form = $this->createForm(TaxeType::class, $taxe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($taxe);
            $this->em->flush();

            $this->addFlash('success', 'Taxe crée avec succès');

            return $this->redirectToRoute('admin.taxe.index');
        }

        return $this->render('Backend/Taxe/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: '.delete', methods: ['POST'])]
    public function delete(?Taxe $taxe, Request $request): Response|RedirectResponse
    {
        if (!$taxe) {
            $this->addFlash('error', 'Taxe Not Found');

            return $this->redirectToRoute('admin.taxe.index');
        }

        if ($this->isCsrfTokenValid('delete'.$taxe->getId(), $request->request->get('token'))) {
            $this->em->remove($taxe);
            $this->em->flush();

            $this->addFlash('success', 'Taxe supprimer avec succes');
        } else {
            $this->addFlash('error', 'Invalide token CSRF');
        }

        return $this->redirectToRoute('admin.taxe.index');
    }
}
