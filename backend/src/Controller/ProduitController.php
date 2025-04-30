<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitForm;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/produit')]
final class ProduitController extends AbstractController
{
        #[Route(name: 'app_produit_index_json', methods: ['GET'])]
    public function indexJson(ProduitRepository $produitRepository): JsonResponse
    {
        $produits = $produitRepository->findAll();

        return $this->json($produits);
    }

    #[Route(name: 'app_produit_index', methods: ['GET'])]
        public function index(ProduitRepository $produitRepository): Response
        {
            return $this->render('produit/index.html.twig', [
                'produits' => $produitRepository->findAll(),
            ]);
        }

        #[Route('/new', name: 'app_produit_new', methods: ['GET', 'POST'])]
        public function new(Request $request, EntityManagerInterface $entityManager): Response
        {
            $produit = new Produit();
            $form = $this->createForm(ProduitForm::class, $produit);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($produit);
                $entityManager->flush();

                return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('produit/new.html.twig', [
                'produit' => $produit,
                'form' => $form,
            ]);
        }

        #[Route('/{id}', name: 'app_produit_show', methods: ['GET'])]
        public function show(Produit $produit): Response
        {
            return $this->render('produit/show.html.twig', [
                'produit' => $produit,
            ]);
        }

        #[Route('/{id}/edit', name: 'app_produit_edit', methods: ['GET', 'POST'])]
        public function edit(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
        {
            $form = $this->createForm(ProduitForm::class, $produit);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->flush();

                return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('produit/edit.html.twig', [
                'produit' => $produit,
                'form' => $form,
            ]);
        }

        #[Route('/api/delete/{id}', name: 'api_produit_delete', methods: ['DELETE', 'GET'])]
        public function deleteApi(Request $request, Produit $produit, EntityManagerInterface $entityManager): JsonResponse
        {
            $entityManager->remove($produit);
            $entityManager->flush();
        
            return new JsonResponse(['message' => 'Produit supprimé avec succès.'], Response::HTTP_NO_CONTENT);
        }
}