<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $products = [
            ['name' => 'IPhone 15 Pro Max, 256', 'price' => 1280],
            ['name' => 'MacBook Pro 16-inch M3 MAX (2023) 14CPU/30GPU, 36GB RAM, 1TB', 'price' => 3600],
            ['name' => 'iPad Pro 13" (2024)', 'price' => 1380],
            ['name' => 'AirPods Max', 'price' => 550],
            ['name' => 'Watch Ultra 2 Titanium Case with Ocean Band', 'price' => 810],
        ];

        foreach ($products as $productData) {
            $product = new Product();
            $product->setName($productData['name']);
            $product->setPrice($productData['price']);
            $manager->persist($product);
        }

        $manager->flush();
    }
}