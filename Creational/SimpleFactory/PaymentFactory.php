<?php

declare(strict_types=1);

namespace DesignPatterns\Creational\SimpleFactory;

use DesignPatterns\Creational\SimpleFactory\Interfaces\PaymentMethod;
use DesignPatterns\Creational\SimpleFactory\Product\CreditCard;
use DesignPatterns\Creational\SimpleFactory\Product\PayPal;
use InvalidArgumentException;

/**
 * Simple Factory - centralized object creation.
 * Client chooses product type via parameter, factory returns appropriate implementation.
 */
class PaymentFactory
{
    public function create(string $type): PaymentMethod
    {
        return match ($type) {
            'card' => new CreditCard(),
            'paypal' => new PayPal(),
            default => throw new InvalidArgumentException("Unknown payment type: {$type}"),
        };
    }
}
