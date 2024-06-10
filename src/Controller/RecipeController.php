<?php

namespace App\Controller;

use App\Repository\RecipeRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/recettes', name: 'recettes.')]
class RecipeController extends AbstractController
{
    /**
     * @Route('/', name: 'index', methods: ['GET'])
     * @param RecipeRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('', name: 'index',methods: ['GET'])]
    public function index(RecipeRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $recettes = $paginator->paginate(
            $repository->findAll(),
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('pages/recipe/index.html.twig', [
            'recettes' => $recettes
        ]);
    }

    /**
     * @Route('/show/{id}', name: 'show', methods: ['GET'])
     * @param RecipeRepository $repository
     * @param int $id
     * @return Response
     */
    #[Route('/{id}', name: 'show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(RecipeRepository $repository, int $id): Response
    {
        $recette = $repository->find($id);
        return $this->render('pages/recipe/show.html.twig', [
            'recette' => $recette
        ]);
    }

    /**
     * @Route('/create', name: 'create', methods: ['GET', 'POST'])
     * @param Request $request
     * @return Response
     */
    #[Route('/create', name: 'create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        return $this->render('pages/recipe/index.html.twig');
    }

    /**
     * @Route('/edit/{id}', name: 'edit', methods: ['GET', 'POST'])
     * @param RecipeRepository $repository
     * @param int $id
     * @param Request $request
     * @return Response
     */
    #[Route('/edit/{id}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(RecipeRepository $repository, int $id, Request $request): Response
    {
        $recette = $repository->find($id);
        return $this->render('pages/recipe/index.html.twig', [
            'recette' => $recette
        ]);
    }

    /**
     * @Route('/delete/{id}', name: 'delete', methods: ['POST'])
     * @param RecipeRepository $repository
     * @param int $id
     * @return Response
     */
    #[Route('/delete/{id}', name: 'delete', methods: ['POST'])]
    public function delete(RecipeRepository $repository, int $id): Response
    {
        $recette = $repository->find($id);
        return $this->redirectToRoute('recettes.index');
    }


}
