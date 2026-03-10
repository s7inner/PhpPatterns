<?php

declare(strict_types=1);

namespace DesignPatterns\Structural\Adapter\Vendors;

class StripeClient
{
    /** @var array<string, mixed> */
    private array $lastPayload = [];

    /**
     * @param array<string, mixed> $payload
     * @return array<string, mixed>
     */
    public function createCharge(array $payload): array
    {
        $this->lastPayload = $payload;

        return [
            'status' => 'succeeded',
            'id' => 'stripe_tx_1',
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
