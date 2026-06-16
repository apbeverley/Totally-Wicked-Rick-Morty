# Rick and Morty Encyclopedia — Build Plan

## Stack Decisions

| Concern              | Decision                           | Rationale                                                                |
|----------------------|------------------------------------|--------------------------------------------------------------------------|
| Framework            | Laravel (PHP 8.3+)                 | Production conventions, MVC out of the box, familiar                     |
| HTTP client          | Laravel `Http` facade              | Idiomatic Laravel, Guzzle under the hood, no extra dependency            |
| Templating           | Vue 3 + Inertia.js                 | Scaffolded via PhpStorm, reactive state, cleaner than Blade + vanilla JS |
| Caching              | Laravel Cache facade (file driver) | Zero infrastructure, works locally, handles API rate limiting            |
| Frontend enhancement | Vue 3 reactive state               | Native reactivity, no vanilla JS needed                                  |

---

## Architecture

```
Controller → RickAndMortyService → RickAndMortyClient → Http facade → API
```

- **RickAndMortyClient** — all HTTP and cache concerns. Unaware of data shape.
- **RickAndMortyService** — maps API responses to typed objects. Unaware of cache.
- **CharacterController** — handles requests, calls service, passes data to views.
- **Character** — readonly DTO, populated via static factory from API response.
- **Custom Exceptions** — typed, with static `throw()` factory method.

---

## Directory Structure

```
app/
├── Exceptions/
│   └── ApiException.php
├── Http/
│   └── Controllers/
│       └── CharacterController.php
├── Models/
│   └── Character.php
├── Services/
│   ├── RickAndMortyClient.php
│   └── RickAndMortyService.php
resources/
└── js/
    └── Pages/
        ├── Characters/
        │   ├── List.vue
        │   └── Character.vue
        └── Layouts/
            └── AppLayout.vue
```

---

## Class Responsibilities

### `RickAndMortyClient`
- Accepts endpoint + params
- Builds cache key from URL/params
- Returns cached response if available
- Fires `Http` request if not cached, stores result
- Catches HTTP failures and re-throws as typed exceptions:
    - `429` → `ApiRateLimitException::throw()`
    - `404` → `ApiNotFoundException::throw()`
    - All others → `ApiUnavailableException::throw()`

### `RickAndMortyService`
- `getCharacters(array $filters, int $page)` — returns paginated character data + pagination info
- `getCharacter(int $id)` — returns a single `Character`
- Maps raw API arrays to `Character` objects via `Character::fromApiResponse()`
- No knowledge of caching or HTTP internals

### `CharacterController`
- `index(Request $request)` — reads filters/page from query string, calls service, renders listing view
- `show(int $id)` — calls service, renders detail view
- Catches custom exceptions and renders appropriate error responses

### `Character`
- Readonly properties: `id`, `name`, `species`, `status`, `gender`, `origin`, `image`, `episodes`
- Static factory: `Character::fromApiResponse(array $data): self`
- Episode URLs mapped to episode numbers in `fromApiResponse` via `basename()`

### `ApiException`
Single exception class with three static factory methods:
```php
public static function notFound(): never
public static function rateLimited(): never
public static function unavailable(): never
```

---

## Routes

```php
Route::get('/', [CharacterController::class, 'index'])->name('characters.index');
Route::get('/{id}', [CharacterController::class, 'show'])->name('characters.show');
```

---

## Character Listing Page (`/`) — `List.vue`

- Receives characters and pagination data as Inertia props
- Displays a paginated grid of characters — image, name, status, species
- Reactive filter form: name search, status, species, gender
- Filters update via `router.get()` preserving state, no full page reload
- Pagination controls next and previous page links

---

## Character Detail Page (`/{id}`) — `Character.vue`

- Receives a single `Character` as an Inertia prop
- Displays: image, name, species, status, gender, origin
- Lists episodes the character appears in
- No additional API calls required for episodes — extracted from URLs in `fromApiResponse`

---

## API Filters Proxied Directly

The Rick and Morty API supports native filtering. Pass through directly:

| Filter      | API param |
|-------------|-----------|
| Name search | `name`    |
| Status      | `status`  |
| Species     | `species` |
| Gender      | `gender`  |

No client-side filtering — all filtering is API-side.

---

## Caching Strategy

- Cache key: derived from an endpoint path with serialized params
- TTL: 60 minutes (balances freshness vs rate limit protection)
- Driver: file (default Laravel, no infrastructure required)
- Location of cache logic: `RickAndMortyClient` only — service and controller are unaware

---

## Error Handling Strategy

| Scenario            | Method                        | User-facing response            |
|---------------------|-------------------------------|---------------------------------|
| Character not found | `ApiException::notFound()`    | 404 view                        |
| Rate limit hit      | `ApiException::rateLimited()` | Friendly message, suggest retry |
| API down / timeout  | `ApiException::unavailable()` | Friendly message, suggest retry |

---

## Build Order

1. Scaffold Laravel + Vue + Inertia via PhpStorm, env config, basic routing
2. `Character` DTO with `fromApiResponse`
3. `ApiException` with `notFound`, `rateLimited`, `unavailable`
4. `RickAndMortyClient` (Http facade + cache + exception mapping)
5. `RickAndMortyService` (getCharacters, getCharacter)
6. `CharacterController` (index + show, returning Inertia responses)
7. `List.vue` — listing, filters, pagination
8. `Character.vue` — character detail
9. README with setup instructions
10. Review commits, clean up

## Additional Decisions

- I have chosen to keep `withoutVerifying` within the connection request to the API. This is because I aware that this will be run locally and is likely to need this option set to false.
