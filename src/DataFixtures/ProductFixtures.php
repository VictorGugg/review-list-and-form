<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $product = new Product();
        $product->setName('FizzUp');
        $product->setPicture('logo_fizzup.png');
        $manager->persist($product);
        $this->addReference('product_fizzup', $product);
        $manager->flush();
    }
}