# Abstract Factory

## What This Pattern Is For

### The Problem

You need to create **multiple related objects** that must work together as a consistent set. Without the pattern:

```php
// Scattered logic - easy to mix incompatible styles
if ($style === 'casual') {
    $chair = new CasualChair();
    $sofa = new CasualSofa();
    $table = new CasualCoffeeTable();
} elseif ($style === 'victorian') {
    $chair = new VictorianChair();
    $sofa = new VictorianSofa();
    $table = new VictorianCoffeeTable();
}
// What if someone mixes CasualChair with VictorianSofa? Inconsistent room.
```

This leads to:
- **Risk of mixing** - nothing prevents combining Casual chair with Victorian sofa
- **Code duplication** - same `if`/`switch` repeated wherever you create furniture
- **Hard to extend** - adding a new style (e.g. Modern) means editing many files

### What Abstract Factory Solves

Each factory produces a **complete family** of compatible products. The client picks one factory and gets a coherent set. You cannot mix styles by accident.

**Furniture analogy:** You choose a showroom (Casual, Victorian, or Modern). The showroom gives you chair, sofa, and table - all in the same style. No need to worry about compatibility.

### Summary

| Problem | What Abstract Factory Does |
|---------|----------------------------|
| Risk of mixing incompatible products | Each factory returns only compatible products |
| Client knows concrete classes | Client works with interfaces only |
| Duplicated creation logic | Each factory encapsulates its family |
| Adding new family = edit many files | Add new factory class, client code unchanged |

### When to Use

- You need to create **families of related objects** (Chair + Sofa + Table, Button + Checkbox, etc.)
- Products must be **compatible** - no mixing Light Button with Dark Checkbox
- You want to **switch entire sets** (theme, platform, style) without changing client code
- You need to add new families (new theme) without touching existing code

---

## Example: Furniture Styles

We have three furniture styles: **Casual**, **Victorian**, **Modern**. Each style has Chair, Sofa, and Coffee Table. All items from one style must go together.

**1. Product interfaces** - contract for each product type:

```php
interface Chair
{
    public function sitOn(): string;
}

interface Sofa
{
    public function sitOn(): string;
}

interface CoffeeTable
{
    public function putOn(): string;
}
```

**2. Abstract factory** - declares methods to create the whole family:

```php
interface FurnitureFactory
{
    public function createChair(): Chair;
    public function createSofa(): Sofa;
    public function createCoffeeTable(): CoffeeTable;
}
```

**3. Concrete products** - one set per style (CasualChair, CasualSofa, CasualCoffeeTable, etc.)

**4. Concrete factories** - each returns products from its family (in `Factories/`):

```php
class CasualFurnitureFactory implements FurnitureFactory
{
    public function createChair(): Chair { return new CasualChair(); }
    public function createSofa(): Sofa { return new CasualSofa(); }
    public function createCoffeeTable(): CoffeeTable { return new CasualCoffeeTable(); }
}

class VictorianFurnitureFactory implements FurnitureFactory { ... }
class ModernFurnitureFactory implements FurnitureFactory { ... }
```

**5. Usage:**

```php
function furnishRoom(FurnitureFactory $factory): void
{
    $chair = $factory->createChair();
    $sofa = $factory->createSofa();
    $table = $factory->createCoffeeTable();
    // All from the same style - guaranteed compatible
}

furnishRoom(new CasualFurnitureFactory());   // Casual room
furnishRoom(new VictorianFurnitureFactory()); // Victorian room
furnishRoom(new ModernFurnitureFactory());    // Modern room
```

The client works only with `FurnitureFactory`. To change style, pass a different factory - no `if`/`switch` in client code.
