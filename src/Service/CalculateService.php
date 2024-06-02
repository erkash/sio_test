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
        ?Coupon $coupon = null
    ): float|int|null {

        if ($coupon) {
            $price = $product->getPrice();

            switch ($coupon->getType()) {
                case CouponTypeEnum::FIXED->value:
                    $price = $product->getPrice() - $coupon->getDiscount();
                    break;
                case CouponTypeEnum::PERCENT->value:
                    $price = $product->getPrice() - ($product->getPrice()  * $coupon->getDiscount() / 100);
                    break;
            }

            return $price + ($price * $taxNumber->getPercent() / 100);
        }

        return $product->getPrice() + ($product->getPrice() * $taxNumber->getPercent() / 100);
    }
}