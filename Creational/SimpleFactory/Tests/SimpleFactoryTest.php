<?php

declare(strict_types=1);

namespace DesignPatterns\Creational\SimpleFactory\Tests;

use DesignPatterns\Creational\SimpleFactory\Product\CreditCard;
use DesignPatterns\Creational\SimpleFactory\Product\PayPal;
use DesignPatterns\Creational\SimpleFactory\PaymentFactory;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class SimpleFactoryTest extends TestCase
{
    private PaymentFactory $factory;

    protected function setUp(): void
    {
        parent::setUp();
        $this->factory = new PaymentFactory();
    }

    public function testCanCreateCreditCard(): void
    {
        $payment = $this->factory->create('card');

        $this->assertInstanceOf(CreditCard::class, $payment);
        $this->assertTrue($payment->pay(100.00));
    }

    public function testCanCreatePayPal(): void
    {
        $payment = $this->factory->create('paypal');

        $this->assertInstanceOf(PayPal::class, $payment);
        $this->assertTrue($payment->pay(50.50));
    }

    public function testThrowsExceptionForUnknownType(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unknown payment type: bitcoin');

        $this->factory->create('bitcoin');
    }
}
