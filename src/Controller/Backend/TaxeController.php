<?php

namespace App\Controller\Backend;

use App\Repository\TaxeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
}
