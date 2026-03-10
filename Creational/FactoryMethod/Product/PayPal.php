<?php

declare(strict_types=1);

namespace DesignPatterns\Creational\FactoryMethod\Product;

use DesignPatterns\Creational\FactoryMethod\Interfaces\PaymentMethod;

class PayPal implements PaymentMethod
{
    public function pay(float $amount): bool
    {
        return true;
    }
}
