<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
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
    #[Route('', name: 'index', methods: ['GET'])]
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
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/create', name: 'create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $manager): Response
    {
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $recipe = $form->getData();
            $manager->persist($recipe);
            $manager->flush();
            $this->addFlash('success', "La recette a bien été ajoutée !");
            return $this->redirectToRoute('recettes.index');
        } elseif ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', "Un problème est survenu lors de la création de la recette");
            return $this->redirectToRoute('recettes.index');
        }
        return $this->render('pages/recipe/create.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @Route('/edit/{id}', name: 'edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])
     * @param Recipe $recipe
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/edit/{id}', name: 'edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function edit(Recipe $recipe, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $recipe = $form->getData();
            $manager->persist($recipe);
            $manager->flush();
            $this->addFlash('success', 'Votre recette a bien été modifiée !');
            return $this->redirectToRoute('recettes.index');
        } elseif ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', 'Un problème est survenu lors de la modification de la recette.');
            return $this->redirectToRoute('recettes.index');
        }
        return $this->render('pages/recipe/edit.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @Route('/delete/{id}', name: 'delete', requirements: ['id' => "\d+"], methods: ['GET'])
     * @param Recipe $recipe
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/delete/{id}', name: 'delete',requirements: ['id'=>"\d+"], methods: ['GET'])]
    public function delete(Recipe $recipe, EntityManagerInterface $manager): Response
    {
        $manager->remove($recipe);
        $manager->flush();
        $this->addFlash('success', 'La recette a bien été supprimée !');
        return $this->redirectToRoute('recettes.index');
    }

}
