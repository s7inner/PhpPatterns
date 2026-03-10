# Simple Factory

*Creating objects by parameter without exposing the instantiation logic to the client.*

**Client* - the code that uses the factory (the part of the system that calls `$factory->create(...)`).

Examples: `CheckoutController` and `OrderService` are clients of `PaymentFactory`.

## What This Pattern Is For

### The Problem

When object creation is scattered across the codebase:

```php
// In controller
if ($request->type === 'card') {
    $payment = new CreditCard();
} else {
    $payment = new PayPal();
}

// In another service - same logic again
if ($type === 'card') {
    $payment = new CreditCard();
} else {
    $payment = new PayPal();
}
```

This leads to:
- **Code duplication** - the same `if`/`switch` logic repeated everywhere
- **Tight coupling** - client code depends on concrete classes (CreditCard, PayPal)
- **Hard to change** - adding a new payment type requires editing many files

### What Simple Factory Solves

All creation logic is centralized in one place - the factory. The client only requests a type and receives an object, without knowing how it was created.

**Restaurant analogy:** You tell the waiter "I want pizza." The kitchen (factory) decides which pizza to make. You get the dish without knowing the recipe.

### Summary

| Problem | What Simple Factory Does |
|---------|--------------------------|
| `new` scattered everywhere | Creation happens in one factory |
| Client tied to concrete classes | Client works with interface, not concrete classes |
| Changes require editing many files | Changes only in the factory |
| Duplicated `if`/`switch` | Single `match`/`switch` in the factory |

### When to Use

- You have several object types and need to choose one based on a parameter
- Creation logic changes often - easier to update only the factory
- You want to avoid repeating `if`/`switch` across the codebase

---

## Example: Payment Methods

We have credit card and PayPal payment. Instead of `new CreditCard()` or `new PayPal()` everywhere, we call the factory with the type.

**1. Interface** - common contract for all payment types:

```php
interface PaymentMethod
{
    public function pay(float $amount): bool;
}
```

**2. Products** - concrete implementations:

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

**3. Factory** - returns the right object by type:

```php
class PaymentFactory
{
    public function create(string $type): PaymentMethod
    {
        return match ($type) {
            'card' => new CreditCard(),
            'paypal' => new PayPal(),
            default => throw new InvalidArgumentException("Unknown payment type: {$type}"),
        };
    }
}
```

**4. Usage:**

```php
$factory = new PaymentFactory();

$payment = $factory->create('paypal');
$payment->pay(150.00);  // PayPal

$payment = $factory->create('card');
$payment->pay(50.00);   // CreditCard
```

The client never calls `new` directly - it only specifies the type and works with the `PaymentMethod` interface.
