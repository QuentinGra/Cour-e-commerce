<?php

namespace App\Controller\Frontend;

use App\Entity\User;
use App\Entity\Address;
use App\Form\AddressType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;

#[Route('/account', name: 'account')]
class AccountController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {
    }

    #[Route('/address', name: '.address', methods: ['GET'])]
    public function indexAddress(): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        return $this->render('Frontend/Account/index.html.twig', [
            'addresses' => $user->getAddresses(),
        ]);
    }

    #[Route('/address/create', name: '.address.create', methods: ['GET', 'POST'])]
    public function createAddress(Request $request): Response | RedirectResponse
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

            return $this->redirectToRoute('account.address');
        }

        return $this->render('Frontend/Account/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/address/{id}/edit', name: '.address.edit', methods: ['GET', 'POST'])]
    public function updateAddress(?Address $address, Request $request): Response | RedirectResponse
    {
        if (!$address) {
            $this->addFlash('error', 'Address Not Found');
            return $this->redirectToRoute('app.account.address');
        }

        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->em->persist($address);
            $this->em->flush();

            $this->addFlash('success', 'Adresse modifier avec success');
            return $this->redirectToRoute('account.address');
        }

        return $this->render('Frontend/Account/edit.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/address/{id}/delete', name: '.address.delete', methods: ['POST'])]
    public function deleteAddress(?Address $address, Request $request): Response | RedirectResponse
    {
        if (!$address) {
            $this->addFlash('error', 'Address Not Found');
            return $this->redirectToRoute('account.address');
        }

        if ($this->isCsrfTokenValid('delete' . $address->getId(), $request->request->get('token'))) {
            $this->em->remove($address);
            $this->em->flush();

            $this->addFlash('success', 'Adresse supprimer avec succes');
        } else {
            $this->addFlash('error', 'Invalide token CSRF');
        }

        return $this->redirectToRoute('account.address');
    }
}
