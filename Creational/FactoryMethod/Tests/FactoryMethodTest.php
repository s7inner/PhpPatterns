<?php

declare(strict_types=1);

namespace DesignPatterns\Creational\FactoryMethod\Tests;

use DesignPatterns\Creational\FactoryMethod\Factories\CreditCardFactory;
use DesignPatterns\Creational\FactoryMethod\Factories\PayPalFactory;
use DesignPatterns\Creational\FactoryMethod\Interfaces\PaymentMethod;
use DesignPatterns\Creational\FactoryMethod\Product\CreditCard;
use DesignPatterns\Creational\FactoryMethod\Product\PayPal;
use PHPUnit\Framework\TestCase;

class FactoryMethodTest extends TestCase
{
    public function testCreditCardFactoryCreatesCreditCard(): void
    {
        $factory = new CreditCardFactory();
        $payment = $factory->createPayment();

        $this->assertInstanceOf(PaymentMethod::class, $payment);
        $this->assertInstanceOf(CreditCard::class, $payment);
        $this->assertTrue($payment->pay(100.00));
    }

    public function testPayPalFactoryCreatesPayPal(): void
    {
        $factory = new PayPalFactory();
        $payment = $factory->createPayment();

        $this->assertInstanceOf(PaymentMethod::class, $payment);
        $this->assertInstanceOf(PayPal::class, $payment);
        $this->assertTrue($payment->pay(50.50));
    }
}
