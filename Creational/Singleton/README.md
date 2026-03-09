# Singleton

*Ensuring a class has only one instance with a global access point.*

## What This Pattern Is For

### The Problem

Without the pattern, multiple parts of the system can create their own instances of a resource:

```php
// In service A
$connection = new DatabaseConnection();

// In service B
$connection = new DatabaseConnection();

// Two connections - wasted resources, possible inconsistent state
```

This leads to:
- **Multiple instances** - each `new` creates a separate object
- **Wasted resources** - e.g. several DB connections instead of one shared
- **Inconsistent state** - e.g. each logger could write to different configs

### What Singleton Solves

The class guarantees **exactly one instance** throughout the app. All code uses the same object via `getInstance()`.

**Lazy initialization:** The instance is created on first use, not at startup.

### Summary

| Problem | What Singleton Does |
|---------|---------------------|
| Multiple instances of a resource | One instance, reused everywhere |
| Wasted resources (connections, etc.) | Single shared instance |
| No global access point | `getInstance()` - same reference from anywhere |
| Direct `new` creates duplicates | Private constructor blocks `new` |

### Real-World Examples

**Database connection**  
One connection (or connection pool manager) for the whole app. Controllers, services, repositories get the same instance via `getInstance()`. Avoids opening many connections to the DB.

**Logger**  
Single logger instance writes to one output (file, stdout). All classes call `Logger::getInstance()->log()` - logs go to the same stream, no duplicated file handles.

**Configuration**  
Config loaded once from file/env. `Config::getInstance()` returns the same object everywhere. No re-reading files, no conflicting settings.

**Cache**  
One in-memory cache for the app. All cache reads/writes use the same instance. Prevents multiple caches with different data.

**Game manager**  
One GameManager controls game state (score, level, pause). Prevents conflicting state when different parts create their own manager.

---

## Example

```php
final class Singleton
{
    private static ?Singleton $instance = null;

    public static function getInstance(): Singleton
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {}      // blocks direct new
    private function __clone() {}          // blocks cloning
    public function __wakeup(): void { throw new Exception("Cannot unserialize singleton"); }
}

// Usage - always the same instance
$first = Singleton::getInstance();
$second = Singleton::getInstance();
$first === $second;  // true
```
