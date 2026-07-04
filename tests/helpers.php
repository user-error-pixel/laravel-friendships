<?php

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

function createUser(array $overrides = [], int $amount = 1)
{
    $users = Collection::times($amount, function () use ($overrides) {
        $unique = (string) Str::uuid();
        return \App\User::query()->create(array_merge([
            'name' => 'Test User',
            'email' => "user-{$unique}@example.test",
            'password' => bcrypt('password'),
        ], $overrides));
    });
    return $amount === 1 ? $users->first() : $users;
}
