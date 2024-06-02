<?php

namespace App\Tests\Controller;


use App\Entity\Coupon;
use App\Entity\Product;
use App\Entity\ValueObject\TaxNumber;
use App\Exception\InvalidCountryCodeException;
use App\Service\CalculateService;
use PHPUnit\Framework\TestCase;

class ProductControllerTest extends TestCase
{
    private CalculateService $calculateService;

    protected function setUp(): void
    {
        $this->calculateService = new CalculateService();
    }

    /**
     * @throws InvalidCountryCodeException
     */
    public function testCalculatePrice()
    {
        $product = new Product();
        $product->setName('Macbook Pro');
        $product->setPrice(3600);

        $taxNumber = new TaxNumber('DE123456789');

        $finalPrice = $this->calculateService->getFinalPrice($product, $taxNumber);
        $expectedPrice = 3600 + (3600 * 0.19);

        $this->assertEquals($expectedPrice, $finalPrice);
    }

    /**
     * @throws InvalidCountryCodeException
     */
    public function testCalculatePriceWithCoupon()
    {
        $product = new Product();
        $product->setName('Macbook Pro');
        $product->setPrice(3600);

        $taxNumber = new TaxNumber('DE123456789');
        $coupon = new Coupon();
        $coupon->setType('percent');
        $coupon->setDiscount(10);
        $finalPrice = $this->calculateService->getFinalPrice($product, $taxNumber, $coupon);

        $discountedPrice = (3600 - 3600 * 0.1);
        $expectedPrice = $discountedPrice + $discountedPrice * 0.19;

        $this->assertEquals($expectedPrice, $finalPrice);
    }

    /**
     * @throws InvalidCountryCodeException
     */
    public function testCalculatePriceInvalidCountryCode()
    {
        $this->expectException(InvalidCountryCodeException::class);
        $this->expectExceptionMessage('Unsupported prefix country code: KG');

        $product = new Product();
        $product->setName('Macbook Pro');
        $product->setPrice(3600);

        $taxNumber = new TaxNumber('KG123456789');
        $this->calculateService->getFinalPrice($product, $taxNumber);
    }

    public function testCalculatePriceInvalidTaxNumber()
    {
        $product = new Product();
        $product->setPrice(100.0);

        $product = new Product();
        $product->setName('Macbook Pro');
        $product->setPrice(3600);

        $taxNumber = new TaxNumber('FR12');

        $this->assertFalse($taxNumber->isValid());
    }
}
