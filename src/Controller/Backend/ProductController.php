<?php

namespace App\Controller\Backend;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

#[Route('/admin/products', name: 'admin.products')]
class ProductController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {
    }

    #[Route('', name: '.index', methods: ['GET'])]
    public function index(ProductRepository $productRepo): Response
    {
        return $this->render('backend/product/index.html.twig', [
            'products' => $productRepo->findAll(),
        ]);
    }

    #[Route('/create', name: '.create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response | RedirectResponse
    {
        $product = new Product();

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->em->persist($product);
            $this->em->flush();

            $this->addFlash('success', 'Produit crée avec succès');

            return $this->redirectToRoute('admin.products.index');
        }

        return $this->render('Backend/Product/create.html.twig', [
            'form' => $form,
        ]);
    }
}
