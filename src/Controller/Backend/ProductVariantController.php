<?php

namespace App\Controller\Backend;

use App\Repository\ProductVariantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/productVariant', name: 'admin.productVariant')]
class ProductVariantController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {
    }

    #[Route('/{id}', name: '', mehtods: ['GET'])]
    public function index(ProductVariantRepository $productVariantRepo): Response
    {
        return $this->render('backend/product_variant/index.html.twig', [
            'productVariants' => $productVariantRepo->findAll(),
        ]);
    }
}
