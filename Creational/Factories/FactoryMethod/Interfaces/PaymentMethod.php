<?php

declare(strict_types=1);

namespace DesignPatterns\Creational\Factories\FactoryMethod\Interfaces;

interface PaymentMethod
{
    public function pay(float $amount): bool;
}
