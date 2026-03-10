<?php

declare(strict_types=1);

namespace DesignPatterns\Structural\Adapter\Adapters;

use DesignPatterns\Structural\Adapter\Interfaces\PaymentGateway;
use DesignPatterns\Structural\Adapter\DTO\PaymentResultDTO;
use DesignPatterns\Structural\Adapter\Vendors\PayPalClient;

class PayPalAdapter implements PaymentGateway
{
    public function __construct(
        private PayPalClient $payPalClient
    ) {
    }

    /**
     * @param array<string, mixed> $meta
     */
    public function charge(float $amount, string $currency, array $meta = []): PaymentResultDTO
    {
        $response = $this->payPalClient->capturePayment([
            'total' => number_format($amount, 2, '.', ''),
            'currency_code' => strtoupper($currency),
            'custom' => $meta,
        ]);

        return new PaymentResultDTO(
            ($response['state'] ?? '') === 'approved',
            $response['transaction_id'] ?? null
        );
    }
}
