<?php

namespace App\Entity\ValueObject;

use App\Enum\CountryCodeEnum;
use App\Exception\InvalidCountryCodeException;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;

final class TaxNumber
{
    #[Assert\NotBlank]
    #[Assert\Choice(callback: [CountryCodeEnum::class, 'getValues'])]
    private string $countryCode;

    #[Assert\NotBlank]
    private string $value;

    public function __construct(string $taxNumber)
    {
        $this->value = $taxNumber;
        $this->countryCode = substr($taxNumber, 0, 2);
    }

    /**
     * @throws InvalidCountryCodeException
     */
    public function getPercent(): float
    {
        return match ($this->countryCode) {
            CountryCodeEnum::GERMANY->value => 19.0,
            CountryCodeEnum::ITALY->value => 22.0,
            CountryCodeEnum::FRANCE->value => 20.0,
            CountryCodeEnum::GREECE->value => 24.0,
            default => throw new InvalidCountryCodeException(
                "Unsupported prefix country code: {$this->countryCode}"
            ),
        };
    }

    public function isValid(): bool
    {
        $validator = Validation::createValidator();
        $violations = $validator->validate($this->value, [
            new Assert\NotBlank(),
            new Assert\Length(['min' => 11, 'max' => 13]),
            new Assert\Regex(['pattern' => '/^[A-Z]{2,4}\d{7,11}$/'])
        ]);

        if (count($violations) > 0) {
            return false;
        }

        return true;
    }
}