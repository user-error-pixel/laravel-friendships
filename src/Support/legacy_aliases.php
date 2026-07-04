<?php

/*
|--------------------------------------------------------------------------
| Legacy Namespace Aliases
|--------------------------------------------------------------------------
|
| These aliases keep older applications working when they still reference
| the original Hootlex namespace.
|
| New applications should use the PixelError namespace directly.
|
*/

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