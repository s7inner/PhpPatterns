# Factory Method

*Letting subclasses decide which class to instantiate.*

## What This Pattern Is For

### The Problem

With Simple Factory, adding a new payment type requires modifying the factory (another `case` in the switch). This violates Open/Closed Principle.

**Open/Closed Principle** - *Software entities (classes, modules, functions, etc.) should be open for extension, but closed for modification.*

You cannot keep editing `create()` in `PaymentFactory` - **Closed for modification**. For new functionality, create new classes (`CreditCardFactory`, `PayPalFactory`, `StripeFactory`) - **Open for extension**.

```php
// Simple Factory - add Stripe = edit this class
class PaymentFactory {
    public function create(string $type): PaymentMethod {
        return match ($type) {
            'card' => new CreditCard(),
            'paypal' => new PayPal(),
            'stripe' => new Stripe(),  // modify existing code
            default => ...
        };
    }
}
```

### What Factory Method Solves

Each concrete factory is a **subclass** that creates **one** product type. To add a new type, create a new factory class - no modification of existing code (Open/Closed). The choice of which product to create is made by **which factory you use**, not by a parameter.

**Simple Factory:** One factory `PaymentFactory` - no subclasses. Client calls `$factory->create('card')` or `$factory->create('paypal')` - choice via parameter (string).

**Factory Method:** Has subclasses `CreditCardFactory`, `PayPalFactory`. Client creates/receives the factory object - `new CreditCardFactory()` or `new PayPalFactory()` - and calls `$factory->createPayment()`. Choice via factory type, not string.

### Summary

| Simple Factory | Factory Method |
|----------------|----------------|
| One class, `match`/`switch` | Interface + subclasses |
| `create('paypal')` - parameter | `createPayment()` - no parameter, type = factory class |
| Add type = modify factory | Add type = add new factory class |
| Violates Open/Closed | Follows Open/Closed |
| Dependency Inversion weaker | Achieves Dependency Inversion |

### Real-World Examples

**Payment methods**  
`CreditCardFactory`, `PayPalFactory`, `StripeFactory` - each creates its payment processor. Checkout receives the factory (from config or user choice); no switch inside checkout.

**Logger (Stdout vs File)**  
`StdoutLoggerFactory` creates `StdoutLogger`; `FileLoggerFactory` creates `FileLogger`. Bootstrap chooses the factory from config.

**Document exporters**  
`PdfExporterFactory`, `ExcelExporterFactory`, `WordExporterFactory` - each creates its format. The export service receives a factory.

**Notification senders**  
`EmailNotificationFactory`, `SmsNotificationFactory` - each creates its sender. The notifier receives the factory.

---

## Example: Payment Methods

Same domain as Simple Factory (CreditCard, PayPal), but different structure: each payment type has its own factory. No parameter - the factory class defines the product.

**1. Product interface:**

```php
interface PaymentMethod
{
    public function pay(float $amount): bool;
}
```

**2. Abstract factory (one method, no parameter):**

```php
interface PaymentFactory
{
    public function createPayment(): PaymentMethod;
}
```

**3. Concrete products** - `CreditCard`, `PayPal`:

```php
class CreditCard implements PaymentMethod
{
    public function pay(float $amount): bool { return true; }
}

class PayPal implements PaymentMethod
{
    public function pay(float $amount): bool { return true; }
}
```

**4. Concrete factories** - each creates one type:

```php
class CreditCardFactory implements PaymentFactory
{
    public function createPayment(): PaymentMethod
    {
        return new CreditCard();
    }
}

class PayPalFactory implements PaymentFactory
{
    public function createPayment(): PaymentMethod
    {
        return new PayPal();
    }
}
```

**5. Usage - choice is the factory, not a parameter:**

```php
// Bootstrap or checkout logic decides which factory (e.g. from user selection)
$factory = $userChosePayPal ? new PayPalFactory() : new CreditCardFactory();

// Client code - no switch, no parameter
$payment = $factory->createPayment();
$payment->pay(150.00);
```

The client works only with `PaymentFactory`. To add Stripe, create `StripeFactory` - no changes to `CreditCardFactory`, `PayPalFactory`, or client.

---

## Laravel: Match in Service Provider (Most Typical in Production)

In real Laravel projects, the factory selection based on config is usually done in a Service Provider. The switch/match runs **once at the composition root**; the client receives the factory through DI and never sees the switch.

**1. Bind the factory in a Service Provider:**

```php
// AppServiceProvider.php or PaymentServiceProvider.php
public function register(): void
{
    $this->app->bind(PaymentFactory::class, function () {
        return match (config('payment.default')) {
            'card' => new CreditCardFactory(),
            'paypal' => new PayPalFactory(),
            'stripe' => new StripeFactory(),
            default => throw new InvalidArgumentException('Unknown payment provider'),
        };
    });
}
```

**2. CheckoutService receives the factory via DI:**

```php
class CheckoutService
{
    public function __construct(
        private PaymentFactory $factory
    ) {}

    public function checkout(float $amount): bool
    {
        $payment = $this->factory->createPayment();
        return $payment->pay($amount);
    }
}
```

The client (`CheckoutService`) is unaware of the config or the match. It only depends on `PaymentFactory`. The switch lives in one place - the composition root - and the client stays clean.
