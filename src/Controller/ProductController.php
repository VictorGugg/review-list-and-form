<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/product', name: 'product_')]
final class ProductController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Product $product): Response
    {
        $reviews = $product->getReviews();

        return $this->render('product/show.html.twig', [
            'product' => $product,
            'reviews' => $reviews
        ]);
    }
}
