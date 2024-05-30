<?php

namespace App\Controller\Frontend;

use App\Entity\User;
use App\Entity\Address;
use App\Form\AddressType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/compte', name: 'app.account')]
class AccountController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {
    }

    #[Route('/adresse', name: '.address', methods: ['GET'])]
    public function indexAddress(): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        return $this->render('Frontend/Account/index.html.twig', [
            'addresses' => $user->getAddresses(),
        ]);
    }

    #[Route('/adresse/create', name: '.address.create', methods: ['GET', 'POST'])]
    public function createAddress(Request $request): Response
    {
        $address = new Address();

        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();

            $address->addUser($user);

            $this->em->persist($address);
            $this->em->flush();

            $this->addFlash('succes', 'Adresse crée avec succès');

            return $this->redirectToRoute('app.account.address');
        }

        return $this->render('Frontend/Account/create.html.twig', [
            'form' => $form,
        ]);
    }
}
