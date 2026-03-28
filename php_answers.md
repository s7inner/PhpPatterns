# 1. Як під капотом реалізовується сервіс контейнер

<details>
<summary>Розкрити:</summary>

Нижче спрощена реалізація контейнера з двома режимами реєстрації:
- `bind` — новий об'єкт при кожному `make()`
- `singleton` — один об'єкт на ключ (кешується в `$instances`)

```php
<?php

declare(strict_types=1);

use Closure;

final class Container
{
    private array $bindings = [];
    private array $instances = [];

    public function singleton(string $abstract, Closure $concrete): void
    {
        $this->bindings[$abstract] = [
            'concrete' => $concrete,
            'isShared' => true,
        ];
    }

    public function bind(string $abstract, Closure $concrete): void
    {
        $this->bindings[$abstract] = [
            'concrete' => $concrete,
            'isShared' => false,
        ];
    }

    public function make(string $abstract): object
    {
        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }

        if (! isset($this->bindings[$abstract])) {
            throw new \InvalidArgumentException("Not bound: {$abstract}");
        }

        // Lazy creation: object is created only on make()
        $object = ($this->bindings[$abstract]['concrete'])();

        if ($this->bindings[$abstract]['isShared']) {
            $this->instances[$abstract] = $object;
        }

        return $object;
    }
}
```

Використання і результат:

```php
interface SingltoneInterface {}
class SingltoneClass implements SingltoneInterface {}

interface BindInterface {}
class BindClass implements BindInterface {}

$c = new Container();

// register
$c->singleton(SingltoneInterface::class, fn() => new SingltoneClass());
$c->bind(BindInterface::class, fn() => new BindClass());

// singleton
$s1 = $c->make(SingltoneInterface::class);
$s2 = $c->make(SingltoneInterface::class);

var_dump($s1 === $s2); // true - same reference
var_dump($s1 instanceof SingltoneInterface); // true

// bind (transient)
$b1 = $c->make(BindInterface::class);
$b2 = $c->make(BindInterface::class);

var_dump($b1 === $b2); // false - different objects
var_dump($b1 instanceof BindInterface); // true
```

Коротко по логіці:

- **`singleton()` і `bind()`**: тільки реєструють залежність у контейнері, нічого не створюють, повертають `void`.
- **`make()`**: створює об'єкт під час резолву і повертає `object`.

- **`$bindings`**: масив для зберігання залежностей.

  Приклад, що лежить у `$bindings`:

```php
[
    'App\Contracts\LoggerInterface' => [
        'concrete' => fn () => new FileLogger(),
        'isShared' => true,
    ],
]
```

- **`$instances`**: масив тільки для `singleton`, де зберігається вже створений об'єкт.

  Приклад, що лежить у `$instances` після першого `make()`:

```text
[
    'App\Contracts\LoggerInterface' => екземпляр FileLogger (той самий об’єкт при наступних make)
]
```

- **Важливо**: це не GoF Singleton у класі, а container-level `shared singleton` (in-memory cache в RAM на час життя контейнера).

</details>

