<?php

namespace App\DataFixtures;

use App\Entity\Coupon;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CouponFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $coupons = [
            ['code' => 'P10', 'type' => 'percent', 'value' => 10],
            ['code' => 'P15', 'type' => 'percent', 'value' => 15],
            ['code' => 'P20', 'type' => 'percent', 'value' => 20],
            ['code' => 'F50', 'type' => 'fixed', 'value' => 100],
            ['code' => 'F100', 'type' => 'fixed', 'value' => 200],
        ];

        foreach ($coupons as $couponData) {
            $coupon = new Coupon();
            $coupon->setCode($couponData['code']);
            $coupon->setType($couponData['type']);
            $coupon->setDiscount($couponData['value']);
            $manager->persist($coupon);
        }

        $manager->flush();
    }
}