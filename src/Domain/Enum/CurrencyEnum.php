<?php

declare(strict_types=1);

namespace App\Domain\Enum;

enum CurrencyEnum: string
{
    case EUR = 'EUR';
    case GBP = 'GBP';
    case PLN = 'PLN';
}
