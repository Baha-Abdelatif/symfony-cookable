<?php

namespace App\Controller;

use App\Repository\IngredientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/ingredients', name: 'ingredients.')]
class IngredientController extends AbstractController
{
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(IngredientRepository $repository): Response
    {
        $ingredients = $repository->findAll();
        return $this->render('pages/ingredient/index.html.twig',[
            'ingredients' => $ingredients
        ]);
    }
}
