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
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/produits')]
final class ProduitController extends AbstractController
{
    #[Route('', name: 'api_produits_list', methods: ['GET'])]
    public function list(ProduitRepository $produitRepository): JsonResponse
    {
        $produits = $produitRepository->findAll();
        return $this->json($produits);
    }

    #[Route('/{id}', name: 'api_produits_show', methods: ['GET'])]
    public function show(Produit $produit): JsonResponse
    {
        return $this->json($produit);
    }

    #[Route('', name: 'api_produits_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        $produit = $serializer->deserialize($request->getContent(), Produit::class, 'json');

        $entityManager->persist($produit);
        $entityManager->flush();

        return $this->json($produit, Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'api_produits_update', methods: ['PUT'])]
    public function update(Request $request, Produit $produit, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        foreach ($data as $key => $value) {
            if (property_exists($produit, $key)) {
                $setter = 'set' . ucfirst($key);
                if (method_exists($produit, $setter)) {
                    $produit->$setter($value);
                }
            }
        }

        $entityManager->flush();

        return $this->json($produit);
    }

    #[Route('/{id}', name: 'api_produits_delete', methods: ['DELETE'])]
    public function delete(Produit $produit, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($produit);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Produit supprimé avec succès.'], Response::HTTP_NO_CONTENT);
    }
}
