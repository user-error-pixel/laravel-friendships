# Release Notes

## v1.0.1 - Legacy Namespace Compatibility

This release adds backwards compatibility support for applications that are still using the original `Hootlex\Friendships` namespace.

The package now supports the newer `PixelError\Friendships` namespace while allowing older projects to continue using the legacy namespace during migration.

---

## Release Summary

Version `v1.0.1` is a compatibility release focused on making the package easier to adopt for existing applications.

Some projects may still have imports like:

```php
use Hootlex\Friendships\Traits\Friendable;
```

This release adds legacy aliases so those applications do not need to immediately replace every old namespace reference.

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

```php
use PixelError\Friendships\Traits\Friendable;
```

Example:

```php
use Illuminate\Foundation\Auth\User as Authenticatable;
use PixelError\Friendships\Traits\Friendable;

class User extends Authenticatable
{
    use Friendable;
}
```

---

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

---

## Status

This is a backwards compatibility release.

The goal is to make migration from the original namespace easier while keeping the maintained fork usable for modern Laravel 12 and Laravel 13 projects.

New applications should use the `PixelError\Friendships` namespace.
Existing applications may continue using the `Hootlex\Friendships` namespace during migration.
