<?php

declare(strict_types=1);

namespace DesignPatterns\Structural\Adapter;

use DesignPatterns\Structural\Adapter\DTO\PaymentResultDTO;
use DesignPatterns\Structural\Adapter\Interfaces\PaymentGateway;

class CheckoutService
{
    public function __construct(
        private PaymentGateway $paymentGateway
    ) {
    }

    public function checkout(float $amount, string $currency = 'USD'): PaymentResultDTO
    {
        return $this->paymentGateway->charge($amount, $currency);
    }
}
