<?php

declare(strict_types=1);

namespace DesignPatterns\Creational\FactoryMethod\Interfaces;

interface PaymentMethod
{
    public function pay(float $amount): bool;
}
