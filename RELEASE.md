# Release Notes

<<<<<<< HEAD
## v1.0.2 - Stability, Legacy Aliases, and Developer Quality

This release improves package stability before larger feature work.

It focuses on backwards compatibility, model naming cleanup, typed method signatures, stronger tests, code style tooling, and static analysis support.
=======
## v1.0.1 - Legacy Namespace Compatibility

This release adds backwards compatibility support for applications that are still using the original `Hootlex\Friendships` namespace.

The package now supports the newer `PixelError\Friendships` namespace while allowing older projects to continue using the legacy namespace during migration.
>>>>>>> 32f48a81a50a2d5492c417310dbe1a1c9f865d4b

---

## Added

<<<<<<< HEAD
- Added legacy namespace aliases for `Hootlex\Friendships`.
- Added a legacy alias for `Hootlex\Friendships\Status`.
- Added a clean `FriendshipGroup` model name.
- Kept the old `FriendFriendshipGroups` model name as a backwards-compatible class.
- Added return types to public friendship APIs.
- Added better PHPUnit tests for aliases, model naming, and return types.
- Added Laravel Pint for code style checks.
- Added PHPStan/Larastan for static analysis.
- Added Composer scripts for formatting, analysis, tests, and CI.
=======
Version `v1.0.1` is a compatibility release focused on making the package easier to adopt for existing applications.

Some projects may still have imports like:

```php
use Hootlex\Friendships\Traits\Friendable;
```
>>>>>>> 32f48a81a50a2d5492c417310dbe1a1c9f865d4b

This release adds legacy aliases so those applications do not need to immediately replace every old namespace reference.

<<<<<<< HEAD
## Changed

- Updated friendship internals to use the cleaner `FriendshipGroup` model.
- Kept old APIs working while improving type clarity.
- Updated the GitHub Actions workflow to run:
  - Composer validation
  - Pint style checks
  - PHPStan/Larastan static analysis
  - PHPUnit tests

---

## Legacy Namespace Support

Existing applications may still use the old namespace:

```php
use Hootlex\Friendships\Traits\Friendable;
use Hootlex\Friendships\Status;
```

New applications should use:
=======
New projects should still use the new namespace:

```php
use PixelError\Friendships\Traits\Friendable;
```

---

## Added

- Added legacy namespace compatibility for `Hootlex\Friendships`.
- Added aliases for old class references.
- Added support for old applications still importing the original namespace.
- Added a smoother migration path from the original package to this maintained fork.

---

## Legacy Namespace Aliases

The following legacy aliases are supported:

```php
class_alias(
    PixelError\Friendships\FriendshipsServiceProvider::class,
    Hootlex\Friendships\FriendshipsServiceProvider::class
);

class_alias(
    PixelError\Friendships\Status::class,
    Hootlex\Friendships\Status::class
);

class_alias(
    PixelError\Friendships\Models\Friendship::class,
    Hootlex\Friendships\Models\Friendship::class
);

class_alias(
    PixelError\Friendships\Models\FriendshipGroup::class,
    Hootlex\Friendships\Models\FriendshipGroup::class
);

class_alias(
    PixelError\Friendships\Traits\Friendable::class,
    Hootlex\Friendships\Traits\Friendable::class
);
```

---

## Why This Matters

Existing applications may still use the original package namespace:

```php
use Hootlex\Friendships\Traits\Friendable;
```

Without compatibility aliases, those projects would need to manually update every import before switching to this fork.

With this release, older projects can upgrade more safely while still having time to migrate to the new namespace.

---

## Recommended New Namespace

New projects should use:
>>>>>>> 32f48a81a50a2d5492c417310dbe1a1c9f865d4b

```php
use PixelError\Friendships\Traits\Friendable;
use PixelError\Friendships\Status;
```

This release keeps the old namespace working through Composer-loaded aliases.

---

<<<<<<< HEAD
## Developer Commands

Run the test suite:

```bash
composer test
```

Run Laravel Pint:

```bash
composer format
```

Check formatting without changing files:

```bash
composer format:test
```

Run PHPStan/Larastan:

```bash
composer analyse
```

Run the full local CI stack:

```bash
composer ci
```
=======
## Legacy Namespace Still Supported

Existing projects may continue using:

```php
use Hootlex\Friendships\Traits\Friendable;
```

Example:

```php
use Illuminate\Foundation\Auth\User as Authenticatable;
use Hootlex\Friendships\Traits\Friendable;

class User extends Authenticatable
{
    use Friendable;
}
```

This is intended as a migration bridge, not the preferred namespace for new applications.

---

## Composer Autoload Requirement

The legacy alias file should be loaded through Composer's `files` autoload section.

Example:

```json
{
    "autoload": {
        "psr-4": {
            "PixelError\\Friendships\\": "src/"
        },
        "files": [
            "src/Support/legacy_aliases.php"
        ]
    }
}
```

After updating Composer autoloading, run:

```bash
composer dump-autoload
```

---

## Compatibility

| PHP | Laravel / Illuminate |
| --- | --- |
| PHP 8.2+ | Laravel 12 |
| PHP 8.3+ | Laravel 12 / 13 |

---

## Upgrade Notes

If you are already using the new namespace, no code changes are required.

If your application still uses the old namespace, this release should allow those imports to continue working:

```php
use Hootlex\Friendships\Traits\Friendable;
use Hootlex\Friendships\Status;
```

You can migrate to the new namespace gradually:

```php
use PixelError\Friendships\Traits\Friendable;
use PixelError\Friendships\Status;
```

---

## Testing Recommended

Before using this release in production, test:

- Existing models using `Hootlex\Friendships\Traits\Friendable`
- New models using `PixelError\Friendships\Traits\Friendable`
- Friendship creation
- Friend request acceptance
- Friend request denial
- Blocking and unblocking
- Friendship groups
- Status constants through both namespaces
- Service provider loading
>>>>>>> 32f48a81a50a2d5492c417310dbe1a1c9f865d4b

---

## Compatibility

<<<<<<< HEAD
| PHP | Laravel / Illuminate |
| --- | --- |
| PHP 8.2+ | Laravel 12 |
| PHP 8.3+ | Laravel 12 / 13 |

---

## Upgrade Notes

After updating, regenerate Composer autoload files:

```bash
composer dump-autoload
```

If you publish config or migrations, compare your existing files before overwriting local changes.

---

## Testing Recommended

Before production use, test:

- Legacy `Hootlex\Friendships` imports
- New `PixelError\Friendships` imports
- Friend requests
- Friend acceptance
- Friend denial
- Blocking and unblocking
- Friend groups
- Pagination
- Mutual friends
=======
This is a backwards compatibility release.

The goal is to make migration from the original namespace easier while keeping the maintained fork usable for modern Laravel 12 and Laravel 13 projects.

New applications should use the `PixelError\Friendships` namespace.
Existing applications may continue using the `Hootlex\Friendships` namespace during migration.
>>>>>>> 32f48a81a50a2d5492c417310dbe1a1c9f865d4b
