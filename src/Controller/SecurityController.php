<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[Route('', name: 'auth.')]
class SecurityController extends AbstractController
{
    #[Route('/login', name: 'login', methods: ['GET', 'POST'])]
    public function login(AuthenticationUtils $authUtils): Response
    {
        $lastUsername = $authUtils->getLastUsername();
        $lastErrors = $authUtils->getLastAuthenticationError();

        return $this->render('pages/security/login.html.twig', [
            'username' => $lastUsername,
            'error' => $lastErrors,
        ]);
    }

    #[Route('/logout', name:'logout',methods: ['GET'])]
    public function logout(): Response
    {
        // controller can be blank: it will never be executed!
        return $this->redirectToRoute('auth.login');
    }
}
