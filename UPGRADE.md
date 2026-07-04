# Upgrade Notes

## Namespace change

The package namespace changed from:

```php
Hootlex\Friendships
```

to:

```php
PixelError\Friendships
```

Update model imports:

```php
use PixelError\Friendships\Traits\Friendable;
```

## Composer package name

The Composer package name is now:

```bash
user-pixel-error/laravel-friendships
```

## Laravel and PHP support

This version supports:

| PHP | Laravel / Illuminate | Notes |
| --- | --- | --- |
| `^8.2` | `^12.0` | Laravel 12-compatible path |
| `^8.3` | `^13.0` | Laravel 13-compatible path |

The package composer constraints are:

```json
"php": "^8.2",
"illuminate/support": "^12.0 || ^13.0"
```

Composer will not install Laravel 13 on PHP 8.2 because Laravel 13 itself requires PHP 8.3+.

## Events

The package now uses Laravel's modern event dispatcher:

```php
Event::dispatch('friendships.sent', [$sender, $recipient]);
```

The public event names remain the same:

- `friendships.sent`
- `friendships.accepted`
- `friendships.denied`
- `friendships.blocked`
- `friendships.unblocked`
- `friendships.cancelled`

## Publishing

```bash
php artisan vendor:publish --provider="PixelError\Friendships\FriendshipsServiceProvider" --tag="friendships-config"
php artisan vendor:publish --provider="PixelError\Friendships\FriendshipsServiceProvider" --tag="friendships-migrations"
php artisan migrate
```

## v1.0.2

This release is a stability release.

### Added

- Legacy `Hootlex\Friendships` namespace aliases.
- Legacy alias support for `Status.php`.
- Clean `FriendshipGroup` model name.
- Backwards-compatible `FriendFriendshipGroups` model class.
- Return types on friendship methods.
- Additional PHPUnit tests.
- Laravel Pint.
- PHPStan/Larastan.

### After updating

Run:

```bash
composer dump-autoload
composer ci
```

New applications should use:

```php
use PixelError\Friendships\Traits\Friendable;
```

Existing applications may continue using the old namespace during migration:

```php
use Hootlex\Friendships\Traits\Friendable;
```
