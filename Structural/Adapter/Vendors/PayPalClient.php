<?php

declare(strict_types=1);

namespace DesignPatterns\Structural\Adapter\Vendors;

class PayPalClient
{
    /** @var array<string, mixed> */
    private array $lastPayload = [];

    /**
     * @param array<string, mixed> $payload
     * @return array<string, mixed>
     */
    public function capturePayment(array $payload): array
    {
        $this->lastPayload = $payload;

        return [
            'state' => 'approved',
            'transaction_id' => 'paypal_tx_1',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function getLastPayload(): array
    {
        return $this->lastPayload;
    }
}
