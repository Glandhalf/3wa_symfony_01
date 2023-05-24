<?php

namespace App\DataFixtures;

use Faker\Factory;
use DateTimeImmutable;
use App\Entity\Product;
use App\Entity\Categorie;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 10; $i++) {
            $categorie = new Categorie();
            $categorie->setName('categorie '.$faker->unique()->word())
            ->setDescription($faker->sentence(10))
            ->setPicture($faker->imageUrl(640, 480, 'animals', true))
            ->setSlug($faker->unique()->word());

            $product = new Product();
            $product->setName('product '.$faker->unique()->word())
            ->setDescription($faker->sentence(10))
            ->setPrice($faker->randomFloat(2, 1, 5000))
            ->setPicture($faker->imageUrl(640, 480, 'animals', true))
            ->setSlug($faker->unique()->word())
            ->setCategorie($categorie)
            ->setCreatedAt(new DateTimeImmutable())
            ->setModifiedAt(new DateTimeImmutable());

            $manager->persist($categorie);
            $manager->persist($product);
        }

        $manager->flush();
    }
}
