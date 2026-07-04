<?php

namespace QuadArena\Friendships;

use Illuminate\Support\ServiceProvider;

class FriendshipsServiceProvider extends ServiceProvider
{
    /**
     * Register package services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/config/friendships.php', 'friendships');
    }

    /**
     * Bootstrap package services.
     */
    public function boot(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $timestamp = date('Y_m_d_His');

        $this->publishes([
            __DIR__ . '/database/migrations/create_friendships_table.php' => database_path("migrations/{$timestamp}_create_friendships_table.php"),
            __DIR__ . '/database/migrations/create_friendships_groups_table.php' => database_path('migrations/' . date('Y_m_d_His', strtotime('+1 second')) . '_create_friendships_groups_table.php'),
        ], 'friendships-migrations');

        $this->publishes([
            __DIR__ . '/config/friendships.php' => config_path('friendships.php'),
        ], 'friendships-config');
    }
}
