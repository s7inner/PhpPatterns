<?php

declare(strict_types=1);

namespace DesignPatterns\Creational\Factories\FactoryMethod\Product;

use DesignPatterns\Creational\Factories\FactoryMethod\Interfaces\PaymentMethod;

class CreditCard implements PaymentMethod
{
    public function pay(float $amount): bool
    {
        return true;
    }
}
