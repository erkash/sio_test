<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;


class Request
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Positive]
        public readonly int $product,
        #[Assert\NotBlank]
        public readonly string $taxNumber,
        #[Assert\Regex(pattern: "/^[A-Z0-9]{3}$/")]
        public readonly ?string $couponCode,
        public readonly ?string $paymentProcessor = null
    ) {
    }
}
