# PixelError Laravel Friendships

This package gives Eloquent models the ability to manage friendships.
You can build friend requests, accepted friendships, blocks, and friend groups.

## Supported versions

| Package version | PHP | Laravel / Illuminate |
| --- | --- | --- |
| Current | `^8.2` | `^12.0` || ^13.0` |

> Laravel 12 supports PHP 8.2+, while Laravel 13 requires PHP 8.3+. Composer will automatically resolve compatible versions based on your project.

## Models can:
- Send friend requests
- Accept friend requests
- Deny friend requests
- Block another model
- Group friends

## Installation

Install the package through Composer.

```bash
composer require user-pixel-error/laravel-friendships
```

Laravel will auto-discover the service provider. Publish the config and migrations when you are ready to customize the table names or groups.

```bash
php artisan vendor:publish --provider="PixelError\Friendships\FriendshipsServiceProvider" --tag="friendships-config"
php artisan vendor:publish --provider="PixelError\Friendships\FriendshipsServiceProvider" --tag="friendships-migrations"
php artisan migrate
```

The config file will be published to:

```text
config/friendships.php
```

## Setup a Model
```php
use Illuminate\Foundation\Auth\User as Authenticatable;
use PixelError\Friendships\Traits\Friendable;

class User extends Authenticatable
{
    use Friendable;
    // ...
}
```

## How to use
[Check the Test file to see the package in action](https://github.com/user-pixel-error/laravel-friendships/blob/master/tests/FriendshipsTest.php)

#### Send a Friend Request
```php
$user->befriend($recipient);
```

#### Accept a Friend Request
```php
$user->acceptFriendRequest($sender);
```

#### Deny a Friend Request
```php
$user->denyFriendRequest($sender);
```

#### Remove Friend
```php
$user->unfriend($friend);
```

#### Block a Model
```php
$user->blockFriend($friend);
```

#### Unblock a Model
```php
$user->unblockFriend($friend);
```

#### Check if Model is Friend with another Model
```php
$user->isFriendWith($friend);
```

#### Check if Model has a pending friend request from another Model
```php
$user->hasFriendRequestFrom($sender);
```

#### Check if Model has already sent a friend request to another Model
```php
$user->hasSentFriendRequestTo($recipient);
```

#### Check if Model has blocked another Model
```php
$user->hasBlocked($friend);
```

#### Check if Model is blocked by another Model
```php
$user->isBlockedBy($friend);
```

#### Get a single friendship
```php
$user->getFriendship($friend);
```

#### Get a list of all Friendships
```php
$user->getAllFriendships();
```

#### Get a list of pending Friendships
```php
$user->getPendingFriendships();
```

#### Get a list of accepted Friendships
```php
$user->getAcceptedFriendships();
```

#### Get a list of denied Friendships
```php
$user->getDeniedFriendships();
```

#### Get a list of blocked Friendships
```php
$user->getBlockedFriendships();
```

#### Get a list of Friend Requests
```php
$user->getFriendRequests();
```

#### Get friends collection
```php
$user->getFriends();
```

#### Get friends paginator
```php
$user->getFriends($perPage = 20);
```

#### Group a friend
```php
$user->groupFriend($friend, 'close_friends');
```

#### Remove a friend from a group
```php
$user->ungroupFriend($friend, 'close_friends');
```

#### Remove a friend from all groups
```php
$user->ungroupFriend($friend);
```

## Legacy namespace support

Older applications may still use the original package namespace:

```php
use Hootlex\Friendships\Traits\Friendable;
use Hootlex\Friendships\Status;
```

This package includes Composer-loaded aliases so older imports can continue working while you migrate to the new namespace.

New applications should use:

```php
use PixelError\Friendships\Traits\Friendable;
use PixelError\Friendships\Status;
```

After installing or updating the package, regenerate Composer autoload files if needed:

```bash
composer dump-autoload
```

## Development tools

This package includes Laravel Pint and PHPStan/Larastan for code quality.

```bash
composer format       # Format code with Laravel Pint
composer format:test  # Check formatting without modifying files
composer analyse      # Run PHPStan/Larastan
composer test         # Run PHPUnit
composer ci           # Run style check, static analysis, and tests
```

## v1.0.2 maintenance focus

Version `v1.0.2` focuses on package stability:

- Fixed legacy namespace aliases
- Added `Status` legacy alias support
- Added cleaner `FriendshipGroup` model naming
- Kept `FriendFriendshipGroups` for backwards compatibility
- Added return types to the friendship API
- Added better tests for aliases, model naming, and return types
- Added Laravel Pint
- Added PHPStan/Larastan
