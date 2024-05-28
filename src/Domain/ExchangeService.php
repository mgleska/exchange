<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\Dto\ExchangeResultDto;
use App\Domain\Enum\CurrencyEnum;
use App\Domain\Exception\RateNotFoundException;
use App\Domain\Repository\RateRepositoryInterface;

class ExchangeService
{
    public function __construct(
        private readonly RateRepositoryInterface $rateRepository,
    ) {
    }

    public const EXCHANGE_FEE_RATE = 0.01;

    public function calculateSellExchange(CurrencyEnum $soldCurrency, CurrencyEnum $baseCurrency, float $amount): ExchangeResultDto
    {
        $rate = $this->rateRepository->getRate($soldCurrency, $baseCurrency);

        if ($rate === null) {
            throw new RateNotFoundException();
        }

        $amountBase = round($amount * $rate, 2);
        $exchangeFee = round($amountBase * self::EXCHANGE_FEE_RATE, 2);

        return new ExchangeResultDto($amountBase, $exchangeFee);
    }

    public function calculateBuyExchange(CurrencyEnum $boughtCurrency, CurrencyEnum $baseCurrency, float $amount): ExchangeResultDto
    {
        $rate = $this->rateRepository->getRate($baseCurrency, $boughtCurrency);

        if ($rate === null) {
            throw new RateNotFoundException();
        }

        $buyRate = 1 / $rate;

        $amountBase = round($amount * $buyRate, 2);
        $exchangeFee = round($amountBase * self::EXCHANGE_FEE_RATE, 2);

        return new ExchangeResultDto($amountBase, $exchangeFee);
    }
}
