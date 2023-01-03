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

            // CREATING NEW REVIEW FOR THE PRODUCT
            $review = new Review();
            $form = $this->createForm(ReviewType::class, $review);
            $form->handleRequest($request);

            $review->setSubmitDate(new DateTime('now'));
            $review->setProduct($product[0]);

            if ($form->isSubmitted() && $form->isValid()) {
                /** @var UploadedFile $pictureFile */
                $pictureFile = $form->get('picture')->getData();

                // As the picture field is optionnal, managing the image file only when a file is uploaded
                if ($pictureFile) {
                    $originalFileName = pathinfo($pictureFile->getClientOriginalName(), PATHINFO_FILENAME);
                    // safely including the file name as part of the URL
                    $safeFileName = $slugger->slug($originalFileName);
                    $newFileName = $safeFileName . '-' . uniqid() . '.' . $pictureFile->guessExtension();

                    // Moving the picture where pictures are stored (defined in config/services.yaml)
                    $pictureFile->move(
                        $this->getParameter('pictures_directory'),
                        $newFileName
                    );

                    // Updating the picture property to store the image file name
                    $review->setPicture($newFileName);
                }

                // Saving the review inside the database
                $reviewRepository->save($review, true);

                // TODO adjust rating of the product based on new rating
                // $this->modifyProductRating();

                // TODO make redirection
                return $this->redirectToRoute('product_show', ($product['id']));
            }

            return $this->render('review/new.html.twig', [
                'review' => $review,
                'form' => $form->createView(),
            ]);
        }
}
