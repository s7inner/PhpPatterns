<?php

declare(strict_types=1);

namespace DesignPatterns\Creational\Factories\SimpleFactory\Product;

use DesignPatterns\Creational\Factories\SimpleFactory\Interfaces\PaymentMethod;

class PayPal implements PaymentMethod
{
    public function pay(float $amount): bool
    {
        return true;
    }
}
