<?php

declare(strict_types=1);

namespace DesignPatterns\Creational\FactoryMethod\Factories;

use DesignPatterns\Creational\FactoryMethod\Interfaces\PaymentFactory;
use DesignPatterns\Creational\FactoryMethod\Interfaces\PaymentMethod;
use DesignPatterns\Creational\FactoryMethod\Product\CreditCard;

class CreditCardFactory implements PaymentFactory
{
    public function createPayment(): PaymentMethod
    {
        return new CreditCard();
    }
}
