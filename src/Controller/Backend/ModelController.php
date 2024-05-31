<?php

namespace App\Controller\Backend;

use App\Entity\Model;
use App\Form\ModelType;
use App\Repository\ModelRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/models', name: 'admin.models')]
class ModelController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {
    }

    #[Route('', name: '.index', methods: ['GET'])]
    public function index(ModelRepository $modelRepo): Response
    {
        return $this->render('Backend/Model/index.html.twig', [
            'models' => $modelRepo->findAll(),
        ]);
    }

    #[Route('/create', name: '.create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response|RedirectResponse
    {
        $model = new Model();

        $form = $this->createForm(ModelType::class, $model);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($model);
            $this->em->flush();

            $this->addFlash('success', 'Model crée avec succès');

            return $this->redirectToRoute('admin.models.index');
        }

        return $this->render('Backend/Model/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: '.edit', methods: ['GET', 'POST'])]
    public function update(?Model $model, Request $request): Response|RedirectResponse
    {
        if (!$model) {
            $this->addFlash('error', 'Model Not Found');

            return $this->redirectToRoute('admin.models.index');
        }

        $form = $this->createForm(ModelType::class, $model);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($model);
            $this->em->flush();

            $this->addFlash('success', 'Model modifié avec succès');

            return $this->redirectToRoute('admin.models.index');
        }

        return $this->render('Backend/Model/edit.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: '.delete', methods: ['POST'])]
    public function delete(?Model $model, Request $request): Response|RedirectResponse
    {
        if (!$model) {
            $this->addFlash('error', 'Model Not Found');

            return $this->redirectToRoute('admin.models.index');
        }

        if ($this->isCsrfTokenValid('delete'.$model->getId(), $request->request->get('token'))) {
            $this->em->remove($model);
            $this->em->flush();

            $this->addFlash('success', 'Model supprimer avec succes');
        } else {
            $this->addFlash('error', 'Invalide token CSRF');
        }

        return $this->redirectToRoute('admin.models.index');
    }
}
