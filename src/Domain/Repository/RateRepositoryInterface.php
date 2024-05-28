<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Enum\CurrencyEnum;

interface RateRepositoryInterface
{
    public function getRate(CurrencyEnum $fromCurrency, CurrencyEnum $toCurrency): ?float;
}
