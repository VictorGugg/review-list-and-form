<?php

namespace App\DataFixtures;

use App\Entity\Review;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

// Using Faker Factory to generate random data
use Faker\Factory;

final class ReviewFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        for($i = 0; $i < 20; $i++) {
            $review = new Review();
            $review->setPseudo($faker->firstName());
            $review->setUserEmail($faker->email());
            $review->setUserRating($faker->numberBetween(4,5));
            $review->setComment($faker->paragraphs(1, true));
            $review->setSubmitDate($faker->dateTime());

            $review->setProduct($this->getReference('product_fizzup'));

            $manager->persist($review);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
          ProductFixtures::class,
        ];
    }
}