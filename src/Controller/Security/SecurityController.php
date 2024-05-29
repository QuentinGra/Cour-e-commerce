<?php

namespace App\Controller\Security;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/', name: 'app')]
class SecurityController extends AbstractController
{
    #[Route('login', name: '.login', methods: ['GET', 'POST'])]
    public function login(AuthenticationUtils $authUtils): Response
    {
        return $this->render('security/login.html.twig', [
            'error' => $authUtils->getLastAuthenticationError(),
            'lastUsername' => $authUtils->getLastUsername(),
        ]);
    }

    #[Route('register', name: '.register', methods: ['GET', 'POST'])]
    public function register(Request $request, UserPasswordHasherInterface $hasher, EntityManagerInterface $em): Response
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $hasher->hashPassword($user, $form->get('password')->getData()),
            );

            $em->persist($user);
            $em->flush();

            // Todo: add flash message

            return $this->redirectToRoute('app.login');
        }

        return $this->render('Security/register.html.twig', [
            'form' => $form
        ]);
    }
}
