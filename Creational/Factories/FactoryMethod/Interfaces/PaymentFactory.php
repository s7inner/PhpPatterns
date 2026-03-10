<?php

declare(strict_types=1);

namespace DesignPatterns\Creational\Factories\FactoryMethod\Interfaces;

interface PaymentFactory
{
    public function createPayment(): PaymentMethod;
}
