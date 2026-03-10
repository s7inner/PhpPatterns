<?php

declare(strict_types=1);

namespace DesignPatterns\Creational\Factories\FactoryMethod\Factories;

use DesignPatterns\Creational\Factories\FactoryMethod\Interfaces\PaymentFactory;
use DesignPatterns\Creational\Factories\FactoryMethod\Interfaces\PaymentMethod;
use DesignPatterns\Creational\Factories\FactoryMethod\Product\CreditCard;

class CreditCardFactory implements PaymentFactory
{
    public function createPayment(): PaymentMethod
    {
        return new CreditCard();
    }
}
