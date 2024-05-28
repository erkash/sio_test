<?php

namespace App\Service\Purchase;

use App\Enum\PaymentProcessorEnum;
use App\Exception\PaymentNotFoundException;
use Systemeio\TestForCandidates\PaymentProcessor\PaypalPaymentProcessor as BasePaypalPaymentProcessor;
use Systemeio\TestForCandidates\PaymentProcessor\StripePaymentProcessor as BaseStripePaymentProcessor;

class PaymentProcessorFactory
{
    /**
     * @throws PaymentNotFoundException
     */
    public static function create(string $processor): PaymentProcessorInterface
    {
        return match ($processor) {
            PaymentProcessorEnum::STRIPE->value => new StripePaymentProcessor(new BaseStripePaymentProcessor()),
            PaymentProcessorEnum::PAYPAL->value => new PaypalPaymentProcessor(new BasePaypalPaymentProcessor()),
            default => throw new PaymentNotFoundException("Unsupported payment processor: $processor"),
        };
    }
}