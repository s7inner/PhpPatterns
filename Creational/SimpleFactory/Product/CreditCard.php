<?php

declare(strict_types=1);

namespace DesignPatterns\Creational\SimpleFactory\Product;

use DesignPatterns\Creational\SimpleFactory\Interfaces\PaymentMethod;

class CreditCard implements PaymentMethod
{
    public function pay(float $amount): bool
    {
        return true;
    }
}
