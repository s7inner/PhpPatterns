<?php

declare(strict_types=1);

namespace DesignPatterns\Creational\FactoryMethod\Interfaces;

interface PaymentFactory
{
    public function createPayment(): PaymentMethod;
}
