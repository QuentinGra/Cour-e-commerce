<?php

namespace App\Controller\Backend;

use App\Entity\Product;
use App\Entity\ProductVariant;
use App\Form\ProductVariantType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

#[Route('/admin/productVariant', name: 'admin.productVariant')]
class ProductVariantController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {
    }

    #[Route('/{id}', name: '.index', methods: ['GET'])]
    public function index(?Product $product): Response
    {
        if (!$product) {
            $this->addFlash('error', 'Product Not Found');

            return $this->redirectToRoute('admin.products.index');
        }

        return $this->render('Backend/ProductVariant/index.html.twig', [
            'productVariants' => $product->getProductVariants(),
        ]);
    }

    #[Route('/create', name: '.create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response | RedirectResponse
    {
        $productVariant = new ProductVariant();

        $form = $this->createForm(ProductVariantType::class, $productVariant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($productVariant);
            $this->em->flush();

            $this->addFlash('success', 'ProductVariant crée avec succès');

            return $this->redirectToRoute('admin.productVariant.index');
        }

        return $this->render('Backend/ProductVariant/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: '.edit', methods: ['GET', 'POST'])]
    public function update(?ProductVariant $productVariant, Request $request): Response | RedirectResponse
    {
        if (!$productVariant) {
            $this->addFlash('error', 'ProductVariant Not Found');

            return $this->redirectToRoute('admin.productVariant.index');
        }

        $form = $this->createForm(ProductVariantType::class, $productVariant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($productVariant);
            $this->em->flush();

            $this->addFlash('success', 'ProductVariant modifié avec succès');

            return $this->redirectToRoute('admin.productVariant.index');
        }

        return $this->render('Backend/ProductVariant/edit.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: '.delete', methods: ['POST'])]
    public function delete(?ProductVariant $productVariant, Request $request): Response|RedirectResponse
    {
        if (!$productVariant) {
            $this->addFlash('error', 'ProductVariant Not Found');

            return $this->redirectToRoute('admin.productVariant.index');
        }

        if ($this->isCsrfTokenValid('delete' . $productVariant->getId(), $request->request->get('token'))) {
            $this->em->remove($productVariant);
            $this->em->flush();

            $this->addFlash('success', 'ProductVariant supprimer avec succes');
        } else {
            $this->addFlash('error', 'Invalide token CSRF');
        }

        return $this->redirectToRoute('admin.productVariant.index');
    }
}
