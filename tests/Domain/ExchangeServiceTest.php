<?php

declare(strict_types=1);

namespace App\Tests\Domain;

use App\Domain\Dto\ExchangeResultDto;
use App\Domain\Enum\CurrencyEnum;
use App\Domain\Exception\RateNotFoundException;
use App\Domain\ExchangeService;
use App\Domain\Repository\RateRepositoryInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\Exception as PHPUnitMockException;
use PHPUnit\Framework\TestCase;

class ExchangeServiceTest extends TestCase
{
    private ExchangeService $sut;

    private RateRepositoryInterface $rateRepository;

    /**
     * @throws PHPUnitMockException
     */
    protected function setUp(): void
    {
        $this->rateRepository = $this->createMock(RateRepositoryInterface::class);
        $this->rateRepository->method('getRate')->willReturnMap([
            [CurrencyEnum::EUR, CurrencyEnum::GBP, 1.5678],
            [CurrencyEnum::GBP, CurrencyEnum::EUR, 1.5432],
        ]);

        $this->sut = new ExchangeService($this->rateRepository);
    }

    #[Test]
    #[DataProvider('dataProviderCalculateSellExchange')]
    public function calculateSellExchange(
        string $soldCurrency,
        string $baseCurrency,
        float $amount,
        float $expectedAmount,
        float $expectedFee,
        string $expectedException,
    ): void {
        if ($expectedException) {
            $this->expectException($expectedException);
        }

        $dto = $this->sut->calculateSellExchange(
            CurrencyEnum::from($soldCurrency),
            CurrencyEnum::from($baseCurrency),
            $amount
        );

        $this->assertInstanceOf(ExchangeResultDto::class, $dto);
        $this->assertSame($expectedAmount, $dto->amountInBaseCurrency);
        $this->assertSame($expectedFee, $dto->exchangeFee);
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public static function dataProviderCalculateSellExchange(): array
    {
        return [
            'sell-100-EUR' => [
                'soldCurrency' => 'EUR',
                'baseCurrency' => 'GBP',
                'amount' => 100.0,
                'expectedAmount' => 156.78,
                'expectedFee' => 1.57,
                'expectedException' => '',
            ],
            'sell-1-EUR' => [
                'soldCurrency' => 'EUR',
                'baseCurrency' => 'GBP',
                'amount' => 1.0,
                'expectedAmount' => 1.57,
                'expectedFee' => 0.02,
                'expectedException' => '',
            ],
            'sell-100-GBP' => [
                'soldCurrency' => 'GBP',
                'baseCurrency' => 'EUR',
                'amount' => 100.0,
                'expectedAmount' => 154.32,
                'expectedFee' => 1.54,
                'expectedException' => '',
            ],
            'sell-1-GBP' => [
                'soldCurrency' => 'GBP',
                'baseCurrency' => 'EUR',
                'amount' => 1.0,
                'expectedAmount' => 1.54,
                'expectedFee' => 0.02,
                'expectedException' => '',
            ],
            'rate-not-found' => [
                'soldCurrency' => 'PLN',
                'baseCurrency' => 'EUR',
                'amount' => 1.0,
                'expectedAmount' => 0,
                'expectedFee' => 0,
                'expectedException' => RateNotFoundException::class,
            ],
        ];
    }

    #[Test]
    #[DataProvider('dataProviderCalculateBuyExchange')]
    public function calculateBuyExchange(
        string $boughtCurrency,
        string $baseCurrency,
        float $amount,
        float $expectedAmount,
        float $expectedFee,
        string $expectedException,
    ): void {
        if ($expectedException) {
            $this->expectException($expectedException);
        }

        $dto = $this->sut->calculateBuyExchange(
            CurrencyEnum::from($boughtCurrency),
            CurrencyEnum::from($baseCurrency),
            $amount
        );

        $this->assertInstanceOf(ExchangeResultDto::class, $dto);
        $this->assertSame($expectedAmount, $dto->amountInBaseCurrency);
        $this->assertSame($expectedFee, $dto->exchangeFee);
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public static function dataProviderCalculateBuyExchange(): array
    {
        return [
            'buy-100-EUR' => [
                'boughtCurrency' => 'EUR',
                'baseCurrency' => 'GBP',
                'amount' => 100.0,
                'expectedAmount' => 64.80,
                'expectedFee' => 0.65,
                'expectedException' => '',
            ],
            'buy-1-EUR' => [
                'boughtCurrency' => 'EUR',
                'baseCurrency' => 'GBP',
                'amount' => 1.0,
                'expectedAmount' => 0.65,
                'expectedFee' => 0.01,
                'expectedException' => '',
            ],
            'buy-100-GBP' => [
                'boughtCurrency' => 'GBP',
                'baseCurrency' => 'EUR',
                'amount' => 100.0,
                'expectedAmount' => 63.78,
                'expectedFee' => 0.64,
                'expectedException' => '',
            ],
            'buy-1-GBP' => [
                'boughtCurrency' => 'GBP',
                'baseCurrency' => 'EUR',
                'amount' => 1.0,
                'expectedAmount' => 0.64,
                'expectedFee' => 0.01,
                'expectedException' => '',
            ],
            'rate-not-found' => [
                'boughtCurrency' => 'PLN',
                'baseCurrency' => 'EUR',
                'amount' => 1.0,
                'expectedAmount' => 0,
                'expectedFee' => 0,
                'expectedException' => RateNotFoundException::class,
            ],
        ];
    }
}
