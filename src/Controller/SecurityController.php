<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[Route('', name: 'auth.')]
/**
 * Class SecurityController
 * @package App\Controller
 */
class SecurityController extends AbstractController
{
    #[Route('/login', name: 'login', methods: ['GET', 'POST'])]
    /**
     * @param AuthenticationUtils $authUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authUtils): Response
    {
        $lastUsername = $authUtils->getLastUsername();
        $lastErrors = $authUtils->getLastAuthenticationError();

        return $this->render('pages/security/login.html.twig', [
            'username' => $lastUsername,
            'error' => $lastErrors,
        ]);
    }

    #[Route('/logout', name: 'logout', methods: ['GET'])]
    /**
     * @codeCoverageIgnore
     */
    public function logout(): Response
    {
        // controller can be blank: it will never be executed!
        return $this->redirectToRoute('auth.login');
    }

    #[Route('/register', name: 'register', methods: ['GET', 'POST'])]
    /**
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function register(Request $request, EntityManagerInterface $manager): Response
    {
        $user = new User();
        $user->setRoles(['ROLE_USER']);
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success', "Inscription réussie ! Veuillez vous connecter pour continuer.");
            return $this->redirectToRoute('auth.login');

        } elseif ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "Veuillez vérifier les informations saisies");
            return $this->redirectToRoute('auth.register');
        }
        return $this->render('pages/security/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
