<?php

namespace App\Service\Purchase;

use Exception;
use Systemeio\TestForCandidates\PaymentProcessor\PaypalPaymentProcessor as BasePaypalPaymentProcessor;

class PaypalPaymentProcessor implements PaymentProcessorInterface
{
    public function __construct(private readonly BasePaypalPaymentProcessor $processor)
    {
    }

    public function pay(float $amount): bool
    {
        try {
            $this->processor->pay((int)($amount * 100));
        } catch (Exception $e) {
            return false;
        }

        return true;
    }
}