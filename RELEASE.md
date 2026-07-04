# Release Notes

## PixelError Laravel Friendships

This release focuses on modernizing the package for newer Laravel applications, mainly by upgrading compatibility for **Laravel 12** and **Laravel 13** while keeping support for **PHP 8.2+**.

---

## Release Summary

This update brings the package forward from its older Laravel compatibility target and prepares it for current Laravel projects.

Main goals:

- Support Laravel 12
- Support Laravel 13
- Support PHP 8.2+
- Modernize package metadata
- Keep the friendship API familiar
- Make installation and usage clearer for new projects

---

## Compatibility

| Package Version | PHP | Laravel / Illuminate |
| --- | --- | --- |
| Current | `^8.2` | `^12.0 || ^13.0` |

### Notes

Laravel 12 supports PHP 8.2 and newer.

Laravel 13 requires a newer PHP version than Laravel 12. Composer will resolve the correct version based on the PHP version used by your project.

---

## What Changed

### Laravel 12 Support

The package now supports Laravel 12 applications.

This means the package can be installed in Laravel 12 projects using modern Illuminate components.

```json
"illuminate/support": "^12.0 || ^13.0"
```

---

### Laravel 13 Support

The package now supports Laravel 13 applications.

This allows newer Laravel projects to use the same friendship system without needing to stay on an older framework version.

---

### PHP 8.2+ Support

The package now supports PHP 8.2 and newer.

```json
"php": "^8.2"
```

This makes the package usable in Laravel 12 applications running PHP 8.2, while still allowing newer PHP versions for Laravel 13 projects.

---

## Namespace / Package Branding

The package has been updated under the PixelError package name.

Use the package with:

```bash
composer require user-pixel-error/laravel-friendships
```

Use the trait with:

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

## Existing API

The main friendship API remains familiar.

Examples:

```php
$user->befriend($recipient);

$user->acceptFriendRequest($sender);

$user->denyFriendRequest($sender);

$user->unfriend($friend);

$user->blockFriend($friend);

$user->unblockFriend($friend);
```

Friendship checks:

```php
$user->isFriendWith($friend);

$user->hasFriendRequestFrom($sender);

$user->hasSentFriendRequestTo($recipient);

$user->hasBlocked($friend);

$user->isBlockedBy($friend);
```

Friendship retrieval:

```php
$user->getAllFriendships();

$user->getPendingFriendships();

$user->getAcceptedFriendships();

$user->getDeniedFriendships();

$user->getBlockedFriendships();

$user->getFriendRequests();

$user->getFriends();
```

Friend groups:

```php
$user->groupFriend($friend, 'close_friends');

$user->ungroupFriend($friend, 'close_friends');

$user->ungroupFriend($friend);
```

---

## Installation

Install the package:

```bash
composer require user-pixel-error/laravel-friendships
```

Publish the config:

```bash
php artisan vendor:publish --provider="PixelError\Friendships\FriendshipsServiceProvider" --tag="friendships-config"
```

Publish the migrations:

```bash
php artisan vendor:publish --provider="PixelError\Friendships\FriendshipsServiceProvider" --tag="friendships-migrations"
```

Run migrations:

```bash
php artisan migrate
```

---

## Upgrade Notes

### From Older Laravel Versions

If you are upgrading an existing project, check the following:

1. Make sure your project is running PHP 8.2 or newer.
2. Make sure your Laravel version is 12 or 13.
3. Update Composer dependencies.
4. Republish package config or compare your existing config with the latest version.
5. Run your application test suite.

---

## Composer Update Example

```bash
composer update user-pixel-error/laravel-friendships
```

Or update all dependencies:

```bash
composer update
```

---

## Configuration

The package config is published to:

```text
config/friendships.php
```

Default config:

```php
<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Friendship Tables
    |--------------------------------------------------------------------------
    |
    | These table names are used by the package to store friendship data.
    | You can change these values if your application uses different table names.
    |
    */

    'tables' => [

        // Stores the main friendship relationships between users.
        'fr_pivot' => 'friendships',

        // Stores which friendship groups a user has assigned to their friends.
        'fr_groups_pivot' => 'user_friendship_groups',
    ],

    /*
    |--------------------------------------------------------------------------
    | Friendship Groups
    |--------------------------------------------------------------------------
    |
    | These are the default friendship groups available in the package.
    | The numeric values are stored in the database, so avoid changing them
    | after users have already started using groups.
    |
    */

    'groups' => [

        // General friends or people the user knows casually.
        'acquaintances' => 0,

        // Friends the user considers closer than normal acquaintances.
        'close_friends' => 1,

        // Friends marked as family members.
        'family' => 2,
    ],

];
```

---

## Testing

Run the package tests with:

```bash
vendor/bin/phpunit
```

If your project has a Composer test script:

```bash
composer test
```

---

## Recommended Before Production

Before using this release in production:

- Run your test suite.
- Test friend request creation.
- Test accepting and denying requests.
- Test blocking and unblocking.
- Test friend groups.
- Test migrations on a local or staging database.
- Confirm your app handles duplicate requests and blocked users correctly.

---

## Status

This release has been modernized for Laravel 12 and Laravel 13 compatibility.

Full production testing is still recommended before relying on it in a live application.
