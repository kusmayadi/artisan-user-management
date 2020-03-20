<?php

namespace Kusmayadi\ArtisanUser;

use Kusmayadi\ArtisanUser\Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use Kusmayadi\ArtisanUser\Models\User;

class DeleteUserTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->faker = \Faker\Factory::create();
        $this->inexistsUser = factory(User::class)->make(['id' => $this->faker->numberBetween(100, 1000)]);
    }

    /**
     * Delete user test with wrong parameter id
     * @test
     */
    function deleteUserWrongParameterId()
    {
        $this->artisan('user:delete ' . $this->inexistsUser->id)
            ->expectsOutput('Delete User')
            ->expectsOutput('========================================')
            ->expectsOutput('User with id ' . $this->inexistsUser->id . " is not exists.\nRun 'php artisan user:list' to see list of users with their ids.")
            ->assertExitCode(0);
    }

    /**
     * Delete user test with wrong parameter email
     * @test
     */
    function deleteUserWrongParameterEmail()
    {
        $this->artisan('user:delete ' . $this->inexistsUser->email)
            ->expectsOutput('Delete User')
            ->expectsOutput('========================================')
            ->expectsOutput('User with email ' . $this->inexistsUser->email . " is not exists.\nRun 'php artisan user:list' to see list of users with their emails.")
            ->assertExitCode(0);
    }

    /**
     * Delete user test with wrong input id
     * @test
     */
    function editUserWrongInputId()
    {
        $this->artisan('user:delete')
            ->expectsOutput('Delete User')
            ->expectsOutput('========================================')
            ->expectsQuestion('Enter user\'s id or email: ', $this->inexistsUser->id)
            ->expectsOutput('========================================')
            ->expectsOutput('User with id ' . $this->inexistsUser->id . " is not exists.\nRun 'php artisan user:list' to see list of users with their ids.")
            ->assertExitCode(0);
    }

    /**
     * Delete user test with wrong input email
     * @test
     */
    function editUserWrongInputEmail()
    {
        $this->artisan('user:delete')
            ->expectsOutput('Delete User')
            ->expectsOutput('========================================')
            ->expectsQuestion('Enter user\'s id or email: ', $this->inexistsUser->email)
            ->expectsOutput('========================================')
            ->expectsOutput('User with email ' . $this->inexistsUser->email . " is not exists.\nRun 'php artisan user:list' to see list of users with their emails.")
            ->assertExitCode(0);
    }

    /**
     * Delete user test by parameter id
     * @test
     */
    function deleteUserByParameterId()
    {
        $user = factory(User::class)->create();

        $this->artisan('user:delete ' . $user->id)
            ->expectsOutput('Delete User')
            ->expectsOutput('========================================')
            ->expectsOutput('ID: ' . $user->id)
            ->expectsOutput('Name: ' . $user->name)
            ->expectsOutput('Email: ' . $user->email)
            ->expectsOutput('========================================')
            ->expectsQuestion('Are you sure you want to delete this user?', 'y')
            ->expectsOutput('User has been remove from the system.')
            ->assertExitCode(0);

        $this->assertDatabaseMissing('users', ['name' => $user->name, 'email' => $user->email]);
    }

    /**
     * Delete user test by parameter id, cancelled
     * @test
     */
    function deleteUserByParameterIdCancel()
    {
        $user = factory(User::class)->create();

        $this->artisan('user:delete ' . $user->id)
            ->expectsOutput('Delete User')
            ->expectsOutput('========================================')
            ->expectsOutput('ID: ' . $user->id)
            ->expectsOutput('Name: ' . $user->name)
            ->expectsOutput('Email: ' . $user->email)
            ->expectsOutput('========================================')
            ->expectsQuestion('Are you sure you want to delete this user?', 'n')
            ->expectsOutput('Aborting.')
            ->assertExitCode(0);

        $this->assertDatabaseHas('users', ['name' => $user->name, 'email' => $user->email]);
    }

    /**
     * Delete user test by parameter email
     * @test
     */
    function deleteUserByParameterEmail()
    {
        $user = factory(User::class)->create();

        $this->artisan('user:delete ' . $user->email)
            ->expectsOutput('Delete User')
            ->expectsOutput('========================================')
            ->expectsOutput('ID: ' . $user->id)
            ->expectsOutput('Name: ' . $user->name)
            ->expectsOutput('Email: ' . $user->email)
            ->expectsOutput('========================================')
            ->expectsQuestion('Are you sure you want to delete this user?', 'y')
            ->expectsOutput('User has been remove from the system.')
            ->assertExitCode(0);

        $this->assertDatabaseMissing('users', ['name' => $user->name, 'email' => $user->email]);
    }

    /**
     * Delete user test by parameter email, cancelled
     * @test
     */
    function deleteUserByParameterEmailCancel()
    {
        $user = factory(User::class)->create();

        $this->artisan('user:delete ' . $user->email)
            ->expectsOutput('Delete User')
            ->expectsOutput('========================================')
            ->expectsOutput('ID: ' . $user->id)
            ->expectsOutput('Name: ' . $user->name)
            ->expectsOutput('Email: ' . $user->email)
            ->expectsOutput('========================================')
            ->expectsQuestion('Are you sure you want to delete this user?', 'n')
            ->expectsOutput('Aborting.')
            ->assertExitCode(0);

        $this->assertDatabaseHas('users', ['name' => $user->name, 'email' => $user->email]);
    }

    /**
     * Delete user test by input id
     * @test
     */
    function deleteUserByInputId()
    {
        $user = factory(User::class)->create();

        $this->artisan('user:delete')
            ->expectsOutput('Delete User')
            ->expectsOutput('========================================')
            ->expectsQuestion('Enter user\'s id or email: ', $user->id)
            ->expectsOutput('========================================')
            ->expectsOutput('ID: ' . $user->id)
            ->expectsOutput('Name: ' . $user->name)
            ->expectsOutput('Email: ' . $user->email)
            ->expectsOutput('========================================')
            ->expectsQuestion('Are you sure you want to delete this user?', 'y')
            ->expectsOutput('User has been remove from the system.')
            ->assertExitCode(0);

        $this->assertDatabaseMissing('users', ['name' => $user->name, 'email' => $user->email]);
    }

    /**
     * Delete user test by input id, cancelled
     * @test
     */
    function deleteUserByInputIdCancel()
    {
        $user = factory(User::class)->create();

        $this->artisan('user:delete')
            ->expectsOutput('Delete User')
            ->expectsOutput('========================================')
            ->expectsQuestion('Enter user\'s id or email: ', $user->id)
            ->expectsOutput('========================================')
            ->expectsOutput('ID: ' . $user->id)
            ->expectsOutput('Name: ' . $user->name)
            ->expectsOutput('Email: ' . $user->email)
            ->expectsOutput('========================================')
            ->expectsQuestion('Are you sure you want to delete this user?', 'n')
            ->expectsOutput('Aborting.')
            ->assertExitCode(0);

        $this->assertDatabaseHas('users', ['name' => $user->name, 'email' => $user->email]);
    }

    /**
     * Delete user test by input email
     * @test
     */
    function deleteUserByInputEmail()
    {
        $user = factory(User::class)->create();

        $this->artisan('user:delete')
            ->expectsOutput('Delete User')
            ->expectsOutput('========================================')
            ->expectsQuestion('Enter user\'s id or email: ', $user->email)
            ->expectsOutput('========================================')
            ->expectsOutput('ID: ' . $user->id)
            ->expectsOutput('Name: ' . $user->name)
            ->expectsOutput('Email: ' . $user->email)
            ->expectsOutput('========================================')
            ->expectsQuestion('Are you sure you want to delete this user?', 'y')
            ->expectsOutput('User has been remove from the system.')
            ->assertExitCode(0);

        $this->assertDatabaseMissing('users', ['name' => $user->name, 'email' => $user->email]);
    }

    /**
     * Delete user test by input email, cancelled
     * @test
     */
    function deleteUserByInputEmailCancel()
    {
        $user = factory(User::class)->create();

        $this->artisan('user:delete')
            ->expectsOutput('Delete User')
            ->expectsOutput('========================================')
            ->expectsQuestion('Enter user\'s id or email: ', $user->email)
            ->expectsOutput('========================================')
            ->expectsOutput('ID: ' . $user->id)
            ->expectsOutput('Name: ' . $user->name)
            ->expectsOutput('Email: ' . $user->email)
            ->expectsOutput('========================================')
            ->expectsQuestion('Are you sure you want to delete this user?', 'n')
            ->expectsOutput('Aborting.')
            ->assertExitCode(0);

        $this->assertDatabaseHas('users', ['name' => $user->name, 'email' => $user->email]);
    }
}
