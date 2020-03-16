<?php

namespace Kusmayadi\ArtisanUser\Tests;

use Kusmayadi\ArtisanUser\ArtisanUserServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->withFactories(__DIR__.'/../database/factories');
    }

    protected function getPackageProviders($app)
    {
        return [
            ArtisanUserServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        include_once __DIR__ . '/../database/migrations/create_users_table.php';
        // run the up() method (perform the migration)
        (new \CreateUsersTable)->up();

        $app['config']->set('auth.providers.users.model', User::class);
    }
}
