<?php

namespace Kusmayadi\ArtisanUser;

use Kusmayadi\ArtisanUser\Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use Kusmayadi\ArtisanUser\Models\User;

class ListUserTest extends TestCase
{
    /**
     * List user test
     * @test
     */
    function listUser()
    {
        $users = factory(User::class, 5)->create();

        $artisan = $this->artisan('user:list');
        $artisan->expectsOutput('Users')
                ->expectsOutput('=====================================================================')
                ->expectsOutput('|  id  |              Name              |           Email           |')
                ->expectsOutput('=====================================================================');

        foreach ($users as $user)
        {
            $artisan->expectsOutput('|' . str_pad($user->id, 6, ' ', STR_PAD_BOTH) . '|' . str_pad($user->name, 32, ' ', STR_PAD_BOTH) . '|' . str_pad($user->email, 27, ' ', STR_PAD_BOTH) . '|');
        }

        $artisan->expectsOutput('=====================================================================')
                ->assertExitCode(0);
    }
}
