<?php

declare(strict_types=1);

namespace App\Domain\Dto;

class ExchangeResultDto
{
    public readonly float $amountInBaseCurrency;
    public readonly float $exchangeFee;

    public function __construct(float $amountInBaseCurrency, float $exchangeFee)
    {
        $this->amountInBaseCurrency = $amountInBaseCurrency;
        $this->exchangeFee = $exchangeFee;
    }
}
