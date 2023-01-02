<?php

namespace App\Controller;

use App\Entity\Review;
use App\Form\ReviewType;
use App\Repository\ProductRepository;
use App\Repository\ReviewRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/review', name: 'review_')]
final class ReviewController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ReviewRepository $reviewRepository): Response
    {
        return $this->render('review/index.html.twig', [
            'reviews' => $reviewRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(
        ProductRepository $productRepository,
        Request $request,
        ReviewRepository $reviewRepository
        ): Response
        {
            // GETTING PRODUCT FROM ID
            // $product = $productRepository->findById($_GET['product_id']);

            // CREATING NEW REVIEW FOR THE PRODUCT
            $review = new Review();
            $form = $this->createForm(ReviewType::class, $review);
            $form->handleRequest($request);

            // TODO setDate
            // $review->setSubmitDate(new DateTime('d/m/Y'));

            if ($form->isSubmitted() && $form->isValid()) {
                $reviewRepository->save($review, true);

                // TODO adjust rating of the product based on new rating
                // $this->modifyProductRating();

                return $this->redirectToRoute('product_show');
            }

            return $this->render('review/new.html.twig', [
                'review' => $review,
                'form' => $form->createView(),
            ]);
        }
}
