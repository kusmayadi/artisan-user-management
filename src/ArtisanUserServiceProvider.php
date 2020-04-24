<?php

namespace Kusmayadi\ArtisanUser;

use Illuminate\Support\ServiceProvider;

class ArtisanUserServiceProvider extends ServiceProvider
{
    public function register()
    {
    }

    public function boot()
    {
        if (! class_exists('Kusmayadi\ArtisanUser\Models\User'))
        {
            class_alias(config('auth.providers.users.model'), 'Kusmayadi\ArtisanUser\Models\User');
        }

        if ($this->app->runningInConsole()) {
            // publish config file

            $this->commands([
                Console\Commands\UserAdd::class,
                Console\Commands\UserList::class,
                Console\Commands\UserEdit::class,
                Console\Commands\UserDelete::class,
                Console\Commands\ResetPassword::class
            ]);
        }
    }
}
