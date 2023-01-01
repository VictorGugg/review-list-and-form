<?php

namespace App\Controller;

use App\Entity\Review;
use App\Form\ReviewType;
use App\Repository\ProductRepository;
use App\Repository\ReviewRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/product/rating')]
class ReviewController extends AbstractController
{
    #[Route('/review', name: 'app_review')]
    public function index(): Response
    {
        return $this->render('review/index.html.twig', [
            'controller_name' => 'ReviewController',
        ]);
    }

    #[Route('/new', name: 'app_review_new', methods: ['GET', 'POST'])]
    public function new(
        ProductRepository $productRepository,
        Request $request,
        ReviewRepository $reviewRepository
        ): Response
        {
            $review = new Review();
            $form = $this->createForm(ReviewType::class, $review);
            $form->handleRequest($request);

            return $this->renderForm('review/new.html.twig');
        }
}
