<?php

namespace App\Service;

use App\Entity\Coupon;
use App\Entity\Product;
use App\Entity\ValueObject\TaxNumber;
use App\Enum\CouponTypeEnum;
use App\Exception\InvalidCountryCodeException;

class CalculateService
{
    /**
     * @throws InvalidCountryCodeException
     */
    public function getFinalPrice(
        Product $product,
        TaxNumber $taxNumber,
        ?Coupon $coupon
    ): float|int|null {
        $finalPrice = $product->getPrice() + ($product->getPrice() * $taxNumber->getPercent() / 100);

        if ($coupon) {
            switch ($coupon->getType()) {
                case CouponTypeEnum::FIXED->value:
                    $finalPrice -= $coupon->getDiscount();
                    break;
                case CouponTypeEnum::PERCENT->value:
                    $finalPrice -= $finalPrice * $coupon->getDiscount() / 100;
                    break;
            }
        }

        return $finalPrice;
    }
}