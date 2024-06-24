<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('', name: 'auth.')]
class SecurityController extends AbstractController
{
    #[Route('/login', name: 'login', methods: ['GET', 'POST'])]
    public function login(): Response
    {
        return $this->render('pages/security/login.html.twig', [
            'controller_name' => 'SecurityController',
        ]);
    }

    #[Route('/logout', name:'logout',methods: ['GET'])]
    public function logout(): Response
    {
        // controller can be blank: it will never be executed!
        return $this->redirectToRoute('auth.login');
    }
}
