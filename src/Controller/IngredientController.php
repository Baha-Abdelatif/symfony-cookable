<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Form\IngredientType;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/ingredients', name: 'ingredients.')]
class IngredientController extends AbstractController
{
    /**
     * @Route('/', name: 'index', methods: ['GET'])
     * @param IngredientRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(IngredientRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $ingredients = $paginator->paginate(
            $repository->findAll(),
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('pages/ingredient/index.html.twig',[
            'ingredients' => $ingredients
        ]);
    }

    /**
     * @Route('/create',name:'create',methods:['GET','POST'])
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/create',name:'create',methods:['GET','POST'])]
    public function create(Request $request, EntityManagerInterface $manager):Response
    {
        $ingredient = new Ingredient();
        $form = $this->createForm(IngredientType::class, $ingredient);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Save the new ingredient to the database
            $ingredient = $form->getData();
            $manager->persist($ingredient);
            $manager->flush();
            $this->addFlash('success', 'Votre ingredient a bien été ajouté à la liste !');
            return $this->redirectToRoute('ingredients.index');
        }elseif ($form->isSubmitted() && !$form->isValid()){
            $this->addFlash('danger', 'Un problème est survenu lors de la création de l\'ingredient');
            return $this->redirectToRoute('ingredients.index');
        }

        return $this->render('pages/ingredient/create.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @Route('/{id}/edit', name: 'edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])
     * @param Ingredient $ingredient
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/{id}/edit', name: 'edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function edit(Ingredient $ingredient, Request $request, EntityManagerInterface $manager):Response
    {
        $form = $this->createForm(IngredientType::class, $ingredient);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Save the edited ingredient to the database
            $ingredient = $form->getData();
            $manager->persist($ingredient);
            $manager->flush();
            $this->addFlash('success', 'Votre ingredient a bien été modifié !');
            return $this->redirectToRoute('ingredients.index');
        }elseif ($form->isSubmitted() && !$form->isValid()){
            $this->addFlash('danger', 'Un problème est survenu lors de la modification de l\'ingredient.');
            return $this->redirectToRoute('ingredients.index');
        }
        return $this->render('pages/ingredient/edit.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @Route('/{id}/delete', name: 'delete', requirements: ['id' => '\d+'], methods: ['GET'])
     * @param Ingredient $ingredient
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/delete/{id}', name: 'delete', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function delete(Ingredient $ingredient, EntityManagerInterface $manager):Response
    {
        $manager->remove($ingredient);
        $manager->flush();
        $this->addFlash('warning', 'Votre ingredient a bien été supprimé de la liste !');
        return $this->redirectToRoute('ingredients.index');
    }
}
