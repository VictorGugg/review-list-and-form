<?php

namespace App\Controller;

use App\Entity\Product;
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
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/review', name: 'review_')]
final class ReviewController extends AbstractController
{
//     #[Route('/', name: 'index')]
//     public function index(ReviewRepository $reviewRepository): Response
//     {
//         return $this->render('review/index.html.twig', [
//             'reviews' => $reviewRepository->findAll(),
//         ]);
//     }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(
        ProductRepository $productRepository,
        Request $request,
        ReviewRepository $reviewRepository,
        SluggerInterface $slugger
        ): Response
        {
            // GETTING PRODUCT FROM ID
            $product = $productRepository->findById($_GET['product_id']);
            $productId = $product[0]->getId();

            // CREATING NEW REVIEW FOR THE PRODUCT
            $review = new Review();
            $form = $this->createForm(ReviewType::class, $review);
            $form->handleRequest($request);

            // SETTING THE SUBMIT DATE TO TODAY
            $review->setSubmitDate(new DateTime('now'));

            // SETTING THE REVIEWED PRODUCT TO THE ONE OBTAINED FROM ID
            $review->setProduct($product[0]);

            // CHECKING IF THE FORM IS SUBMITTED AND VALID
            if ($form->isSubmitted() && $form->isValid()) {
                // GETTING DATA FROM THE PRODUCT FIELD IN FORM, STORING IT INTO A VARIABLE
                /** @var UploadedFile $pictureFile */
                $pictureFile = $form->get('picture')->getData();

                $this->checkForPictureFile(
                    $review,
                    $pictureFile,$slugger);

                // SAVING THE REVIEW INSIDE THE DATABASE
                $reviewRepository->save($review, true);

                // ADJUST TOTAL RATING OF THE PRODUCT BY INCLUDING THE NEW USER RATING
                $this->modifyTotalRating(
                    $review->getUserRating(),
                    $reviewRepository,
                    $productRepository,
                    $product[0]);

                return $this->redirectToRoute('product_show', ['id' => $productId]);
            }

            return $this->render('review/new.html.twig', [
                'review' => $review,
                'form' => $form->createView(),
            ]);
        }

        private function checkForPictureFile($review, $pictureFile, $slugger)
        {
            // As the picture field is optionnal, managing the image file only when a file is uploaded
            if ($pictureFile) {
                $originalFileName = pathinfo($pictureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // using slug to safely include the file name as part of the URL
                $safeFileName = $slugger->slug($originalFileName);
                // using guessExtension to prevent malicious informations coming from user
                $newFileName = $safeFileName . '-' . uniqid() . '.' . $pictureFile->guessExtension();

                // Moving the picture where pictures are stored (defined in config/services.yaml)
                $pictureFile->move(
                    $this->getParameter('pictures_directory'),
                    $newFileName
                );

                // UPDATING THE PICTURE PROPERTY TO STORE THE IMAGE FILE NAME (as string)
                $review->setPicture($newFileName);
            }
        }

        /**
         * Modifying note when user modify his note for a menu
         */
        private function modifyTotalRating(
            string $userRating,
            ReviewRepository $reviewRepository,
            ProductRepository $productRepository,
            Product $product): void
        {
            // GETTING THE SPECIFIC PRODUCT TO RATE AND ALL ITS RATINGS INFORMATIONS
            $productRating = $reviewRepository->findAllRatingsByProduct($product->getId());

            // GETTING THE TOTAL NUMBER OF RATINGS FOR THE PRODUCT
            $numberOfRatings = $productRating[0]['totalRatings'];

            // GETTING THE SUM OF ALL RATINGS, DIVIDED BY THE NUMBER OF ALL RATINGS
            $finalRating = $productRating[0]['ratings'] / $numberOfRatings;

            // SETTING THE FINAL RATING TO THE PRODUCT ENTITY
            $product->setRating($finalRating);

            // SAVING THE CHANGES INSIDE THE DATABASE
            $productRepository->save($product, true);
        }
}
