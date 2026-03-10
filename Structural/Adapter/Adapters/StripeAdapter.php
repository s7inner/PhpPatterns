<?php

declare(strict_types=1);

namespace DesignPatterns\Structural\Adapter\Adapters;

use DesignPatterns\Structural\Adapter\Interfaces\PaymentGateway;
use DesignPatterns\Structural\Adapter\DTO\PaymentResultDTO;
use DesignPatterns\Structural\Adapter\Vendors\StripeClient;

class StripeAdapter implements PaymentGateway
{
    public function __construct(
        private StripeClient $stripeClient
    ) {
    }

    /**
     * @param array<string, mixed> $meta
     */
    public function charge(float $amount, string $currency, array $meta = []): PaymentResultDTO
    {
        $response = $this->stripeClient->createCharge([
            'amount' => (int) round($amount * 100),
            'currency' => strtolower($currency),
            'metadata' => $meta,
        ]);

        return new PaymentResultDTO(
            ($response['status'] ?? '') === 'succeeded',
            $response['id'] ?? null
        );
    }
}
