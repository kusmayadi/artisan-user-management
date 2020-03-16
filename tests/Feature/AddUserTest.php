<?php

namespace Kusmayadi\ArtisanUser;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Kusmayadi\ArtisanUser\Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use Kusmayadi\ArtisanUser\Models\User;

class AddUserTest extends TestCase
{
    /**
     * Add user test
     * @test
     */
    function addUser()
    {
        $user = factory(User::class)->make();

        $this->artisan('user:add')
             ->expectsOutput('Add new user')
             ->expectsOutput('========================================')
             ->expectsQuestion('Name: ', $user->name)
             ->expectsQuestion('Email: ', $user->email)
             ->expectsQuestion('Password: ', $user->password)
             ->expectsQuestion('Re-enter password: ', $user->password)
             ->expectsOutput('========================================')
             ->expectsOutput('This following user will be added to the system:')
             ->expectsOutput('Name: ' . $user->name)
             ->expectsOutput('Email: ' . $user->email)
             ->expectsOutput('========================================')
             ->expectsQuestion('Do you wish to continue? (Y/N)', 'Y')
             ->expectsOutput($user->name . ' has been added to the system.')
             ->assertExitCode(0);

        $this->assertDatabaseHas('users', [
            'name' => $user->name,
            'email' => $user->email
        ]);
    }

    /**
     * Add user cancel test
     * @test
     */
    function addUserCancel()
    {
        $user = factory(User::class)->make();

        $this->artisan('user:add')
             ->expectsOutput('Add new user')
             ->expectsOutput('========================================')
             ->expectsQuestion('Name: ', $user->name)
             ->expectsQuestion('Email: ', $user->email)
             ->expectsQuestion('Password: ', $user->password)
             ->expectsQuestion('Re-enter password: ', $user->password)
             ->expectsOutput('========================================')
             ->expectsOutput('This following user will be added to the system:')
             ->expectsOutput('Name: ' . $user->name)
             ->expectsOutput('Email: ' . $user->email)
             ->expectsOutput('========================================')
             ->expectsQuestion('Do you wish to continue? (Y/N)', 'N')
             ->expectsOutput('Aborting')
             ->assertExitCode(0);

        $this->assertDatabaseMissing('users', [
            'name' => $user->name,
            'email' => $user->email
        ]);
    }

    /**
     * Entering different password test
     * @test
     */
    function differentPassword()
    {
        $user = factory(User::class)->make();

        $this->artisan('user:add')
             ->expectsOutput('Add new user')
             ->expectsOutput('========================================')
             ->expectsQuestion('Name: ', $user->name)
             ->expectsQuestion('Email: ', $user->email)
             ->expectsQuestion('Password: ', $user->password)
             ->expectsQuestion('Re-enter password: ', md5(rand()))
             ->expectsOutput('Password doesn\'t match.')
             ->expectsQuestion('Password: ', $user->password)
             ->expectsQuestion('Re-enter password: ', $user->password)
             ->expectsOutput('========================================')
             ->expectsOutput('This following user will be added to the system:')
             ->expectsOutput('Name: ' . $user->name)
             ->expectsOutput('Email: ' . $user->email)
             ->expectsOutput('========================================')
             ->expectsQuestion('Do you wish to continue? (Y/N)', 'N')
             ->expectsOutput('Aborting')
             ->assertExitCode(0);
    }
}
