<?php

declare(strict_types=1);

namespace DesignPatterns\Structural\Adapter\Tests;

use DesignPatterns\Structural\Adapter\Adapters\PayPalAdapter;
use DesignPatterns\Structural\Adapter\Adapters\StripeAdapter;
use DesignPatterns\Structural\Adapter\CheckoutService;
use DesignPatterns\Structural\Adapter\DTO\PaymentResultDTO;
use DesignPatterns\Structural\Adapter\Vendors\PayPalClient;
use DesignPatterns\Structural\Adapter\Vendors\StripeClient;
use PHPUnit\Framework\TestCase;

class AdapterTest extends TestCase
{
    public function testStripeAdapterConvertsAmountToCentsAndLowercaseCurrency(): void
    {
        $stripeClient = new StripeClient();
        $gateway = new StripeAdapter($stripeClient);

        $result = $gateway->charge(10.50, 'USD', ['order_id' => 'A-1']);
        $payload = $stripeClient->getLastPayload();

        $this->assertTrue($result->isSuccess());
        $this->assertSame('stripe_tx_1', $result->getTransactionId());
        $this->assertSame(1050, $payload['amount']);
        $this->assertSame('usd', $payload['currency']);
        $this->assertSame(['order_id' => 'A-1'], $payload['metadata']);
    }

    public function testPayPalAdapterFormatsAmountAndUppercaseCurrency(): void
    {
        $payPalClient = new PayPalClient();
        $gateway = new PayPalAdapter($payPalClient);

        $result = $gateway->charge(10.5, 'usd', ['order_id' => 'B-1']);
        $payload = $payPalClient->getLastPayload();

        $this->assertTrue($result->isSuccess());
        $this->assertSame('paypal_tx_1', $result->getTransactionId());
        $this->assertSame('10.50', $payload['total']);
        $this->assertSame('USD', $payload['currency_code']);
        $this->assertSame(['order_id' => 'B-1'], $payload['custom']);
    }

    public function testCheckoutServiceWorksWithAnyPaymentGateway(): void
    {
        $serviceWithStripe = new CheckoutService(new StripeAdapter(new StripeClient()));
        $serviceWithPayPal = new CheckoutService(new PayPalAdapter(new PayPalClient()));

        $stripeResult = $serviceWithStripe->checkout(20.00, 'USD');
        $payPalResult = $serviceWithPayPal->checkout(20.00, 'USD');

        $this->assertInstanceOf(PaymentResultDTO::class, $stripeResult);
        $this->assertInstanceOf(PaymentResultDTO::class, $payPalResult);
        $this->assertTrue($stripeResult->isSuccess());
        $this->assertTrue($payPalResult->isSuccess());
    }
}
