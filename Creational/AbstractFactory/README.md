# Abstract Factory

## What This Pattern Is For

*Creating families of related objects*

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

### Real-World Examples

**UI theme switching (Dark/Light)**  
`DarkThemeFactory` creates DarkButton, DarkInput, DarkModal; `LightThemeFactory` creates LightButton, LightInput, LightModal. User selects theme once - all components match. No mixing DarkButton with LightInput.

**Database drivers**  
`MySQLFactory` creates MySQLConnection, MySQLQueryBuilder, MySQLResultSet; `PostgresFactory` does the same for PostgreSQL. The application uses one factory per DB - all parts stay compatible.

**Notification channels (Production/Sandbox)**  
`ProductionNotificationFactory` creates real EmailSender, SmsSender; `SandboxNotificationFactory` creates mocks or test implementations. Same client code works in both environments.

**Cross-platform file writing (Unix/Windows)**  
`UnixWriterFactory` returns UnixCsvWriter (`\n`) and UnixJsonWriter; `WinWriterFactory` returns WinCsvWriter (`\r\n`) and WinJsonWriter. Build pipelines use one factory so all writers share the same line endings. ([DesignPatternsPHP](https://designpatternsphp.readthedocs.io/en/latest/Creational/AbstractFactory/README.html))

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

**3. Concrete products** - one set per style. Example for Casual:

```php
class CasualChair implements Chair
{
    public function sitOn(): string { return 'Sitting on a casual chair'; }
}

class CasualSofa implements Sofa
{
    public function sitOn(): string { return 'Sitting on a casual sofa'; }
}

class CasualCoffeeTable implements CoffeeTable
{
    public function putOn(): string { return 'Put something on a casual coffee table'; }
}
```

Same structure for Victorian (VictorianChair, VictorianSofa, VictorianCoffeeTable) and Modern.

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

**5. Client code** - `RoomFurnisher` works only with the factory interface. No `if`/`switch` on style:

```php
class RoomFurnisher
{
    public static function furnishRoom(FurnitureFactory $factory): array
    {
        return [
            'chair' => $factory->createChair(),
            'sofa' => $factory->createSofa(),
            'table' => $factory->createCoffeeTable(),
        ];
    }
}

// Caller chooses style by passing the factory. RoomFurnisher never knows Casual/Victorian/Modern.
$casualRoom = RoomFurnisher::furnishRoom(new CasualFurnitureFactory());
$victorianRoom = RoomFurnisher::furnishRoom(new VictorianFurnitureFactory());
$modernRoom = RoomFurnisher::furnishRoom(new ModernFurnitureFactory());
```

The client (`RoomFurnisher`) has zero style-specific logic. To change style, pass a different factory.
