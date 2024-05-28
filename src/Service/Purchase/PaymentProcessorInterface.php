<?php

namespace App\Service\Purchase;

interface PaymentProcessorInterface
{
    public function pay(float $amount): bool;
}