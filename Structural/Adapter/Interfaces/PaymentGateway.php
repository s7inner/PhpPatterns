<?php

declare(strict_types=1);

namespace DesignPatterns\Structural\Adapter\Interfaces;

use DesignPatterns\Structural\Adapter\DTO\PaymentResultDTO;

interface PaymentGateway
{
    /**
     * @param array<string, mixed> $meta
     */
    public function charge(float $amount, string $currency, array $meta = []): PaymentResultDTO;
}
