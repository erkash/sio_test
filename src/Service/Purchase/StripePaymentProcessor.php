<?php

namespace App\Service\Purchase;
use Systemeio\TestForCandidates\PaymentProcessor\StripePaymentProcessor as BaseStripePaymentProcessor;


class StripePaymentProcessor implements PaymentProcessorInterface
{
    public function __construct(private readonly BaseStripePaymentProcessor $processor)
    {
    }

    public function pay(float $amount): bool
    {
        return $this->processor->processPayment($amount);
    }
}