<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\Request;
use App\Entity\ValueObject\TaxNumber;
use App\Exception\InvalidCountryCodeException;
use App\Exception\InvalidTaxNumberException;
use App\Exception\PaymentNotFoundException;
use App\Exception\ProductNotFoundException;
use App\Repository\CouponRepository;
use App\Repository\ProductRepository;
use App\Service\CalculateService;
use App\Service\Purchase\PaymentProcessorFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{
    #[Route('/calculate-price', methods: ['POST'])]
    public function calculatePrice(
        #[MapRequestPayload(
            acceptFormat: 'json',
            validationFailedStatusCode: Response::HTTP_BAD_REQUEST
        )] Request $request,
        ProductRepository $productRepository,
        CouponRepository $couponRepository,
        CalculateService $calculateService
    ): JsonResponse {
        try {
            $product = $productRepository->find($request->product);
            if (!$product) {
                throw new ProductNotFoundException('Product not found');
            }

            $taxNumber = new TaxNumber($request->taxNumber);
            if (!$taxNumber->isValid()) {
                throw new InvalidTaxNumberException();
            }

            $coupon = $couponRepository->findOneBy(['code' => $request->couponCode]);
            $finalPrice = $calculateService->getFinalPrice($product, $taxNumber, $coupon);

            return new JsonResponse(
                [
                    'status' => 'success',
                    'price' => $finalPrice,
                    'productName' => $product->getName(),
                ],
                Response::HTTP_OK
            );
        } catch (
            ProductNotFoundException |
            InvalidTaxNumberException |
            InvalidCountryCodeException $e
        ) {
            return new JsonResponse(
                [
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    #[Route('/purchase', methods: ['POST'], format: 'json')]
    public function purchase(
        #[MapRequestPayload] Request $request,
        ProductRepository $productRepository,
        CouponRepository $couponRepository
    ): JsonResponse {
        $product = $productRepository->find($request->product);
        $coupon = $couponRepository->findOneBy(['code' => $request->couponCode]);
        try {
            $processor = PaymentProcessorFactory::create($request->paymentProcessor);
            return new JsonResponse(
                [
                    'status' => 'success',
                    'price' => $product->getPrice(),
                    'productName' => $product->getName(),
                    'processor' => $processor->pay($product->getPrice())
                ],
                Response::HTTP_OK
            );
        } catch (PaymentNotFoundException $e) {
            return new JsonResponse(
                [
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
}
