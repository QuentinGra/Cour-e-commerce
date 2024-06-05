<?php

namespace App\Controller\Frontend;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/', name: 'app')]
class HomeController extends AbstractController
{
    #[Route('', name: '.home', methods: ['GET'])]
    public function index(ProductRepository $productRepo): Response
    {
        return $this->render('Frontend/Home/index.html.twig', [
            'products' => $productRepo->findAll(),
        ]);
    }
}
