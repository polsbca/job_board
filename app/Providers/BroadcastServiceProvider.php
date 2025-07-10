<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register any channels your application needs
        Broadcast::routes();

        /*
         * Example of private channel authorization:
         * Broadcast::channel('user.{id}', function ($user, $id) {
         *     return (int) $user->id === (int) $id;
         * });
         */
    }
}
