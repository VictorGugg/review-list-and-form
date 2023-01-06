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
    // Used in HomeController to display the product list on the home page
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

        $this->displayGlobalRating($reviewRepository, $product);

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

        $this->displayGlobalRating($reviewRepository, $product);
        

        return $this->render('product/show.html.twig', [
            'product' => $product,
            'reviews' => $reviews
        ]);
    }

        public function displayGlobalRating (
            ReviewRepository $reviewRepository,
            Product $product): void
        {
            // DISPLAY GLOBAL RATING
            // ! Only useful when generating fake data, can be removed for production
            $rating = $reviewRepository->findAllRatingsByProduct($product->getId());
            $numberOfRatings = $rating[0]['totalRatings'];
            $finalRating = $rating[0]['ratings'] / $numberOfRatings;
            $product->setRating($finalRating);
        }
}
