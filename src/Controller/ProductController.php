<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Review;
use App\Repository\ProductRepository;
use App\Repository\ReviewRepository;
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
    public function show(
        Product $product,
        ReviewRepository $reviewRepository): Response
    {
        $reviews = $product->getReviews();
        $reviews = $reviewRepository->findBy(array(), array('submitDate' => 'DESC'));

        return $this->render('product/show.html.twig', [
            'product' => $product,
            'reviews' => $reviews
        ]);
    }

    #[Route('/{id}/{rating}', name: 'show_by_rating', methods: ['GET'])]
    public function showRating(
        Product $product,
        ReviewRepository $reviewRepository,
        $rating): Response
    {
        $reviews = $product->getReviews();
        $reviews = $reviewRepository->findBy(array('user_rating' => $rating), array());
        

        return $this->render('product/show.html.twig', [
            'product' => $product,
            'reviews' => $reviews
        ]);
    }

    #[Route('/{id}/{sort}/{order}', name: 'sort_by_order', methods: ['GET'])]
    public function sortByOrder(
        Product $product,
        ReviewRepository $reviewRepository,
        $sort,
        $order): Response
    {
        $reviews = $product->getReviews();
        $reviews = $reviewRepository->findBy(array(), array($sort => $order));
        

        return $this->render('product/show.html.twig', [
            'product' => $product,
            'reviews' => $reviews
        ]);
    }
}
