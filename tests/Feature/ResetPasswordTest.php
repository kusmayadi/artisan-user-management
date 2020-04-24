<?php

namespace Kusmayadi\ArtisanUser;

use Kusmayadi\ArtisanUser\Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use Kusmayadi\ArtisanUser\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ResetPasswordTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->faker = \Faker\Factory::create();
        $this->inexistsUser = factory(User::class)->make(['id' => $this->faker->numberBetween(100, 1000)]);
        $this->existingUser = factory(User::class)->create();
    }

    /**
     * Edit user test with wrong parameter id
     * @test
     */
    function resetPasswordWrongParameterId()
    {
        $this->artisan('user:reset-password ' . $this->inexistsUser->id)
            ->expectsOutput('User with id ' . $this->inexistsUser->id . " is not exists.\nRun 'php artisan user:list' to see list of users with their ids.")
            ->assertExitCode(0);
    }

    /**
     * Reset password test with wrong parameter email
     * @test
     */
    function resetPasswordWrongParameterEmail()
    {
        $this->artisan('user:reset-password ' . $this->inexistsUser->email)
            ->expectsOutput('User with email ' . $this->inexistsUser->email . " is not exists.\nRun 'php artisan user:list' to see list of users with their emails.")
            ->assertExitCode(0);
    }

    /**
     * Reset passwrod test with wrong input id
     * @test
     */
    function resetPasswordWrongInputId()
    {
        $this->artisan('user:reset-password')
            ->expectsOutput('Reset Password')
            ->expectsOutput('========================================')
            ->expectsQuestion('Enter user\'s id or email: ', $this->inexistsUser->id)
            ->expectsOutput('User with id ' . $this->inexistsUser->id . " is not exists.\nRun 'php artisan user:list' to see list of users with their ids.")
            ->assertExitCode(0);
    }

    /**
     * Reset password test with wrong input email
     * @test
     */
    function resetPasswordWrongInputEmail()
    {
        $this->artisan('user:reset-password')
            ->expectsOutput('Reset Password')
            ->expectsOutput('========================================')
            ->expectsQuestion('Enter user\'s id or email: ', $this->inexistsUser->email)
            ->expectsOutput('User with email ' . $this->inexistsUser->email . " is not exists.\nRun 'php artisan user:list' to see list of users with their emails.")
            ->assertExitCode(0);
    }

    /**
     * Reset password with parameter test, Cancelled
     * @test
     */
    function resetPasswordWithParameterCancel()
    {
        $this->artisan('user:reset-password ' . $this->existingUser->id)
            ->expectsOutput('Reset Password')
            ->expectsOutput('========================================')
            ->expectsOutput('ID: ' . $this->existingUser->id)
            ->expectsOutput('Name: ' . $this->existingUser->name)
            ->expectsOutput('Email: ' . $this->existingUser->email)
            ->expectsOutput('========================================')
            ->expectsQuestion('Enter new password or leave it blank if you want to generate random password: ', '')
            ->expectsQuestion('Are you sure you want to reset password for this user ?', 'n')
            ->expectsOutput('Aborting')
            ->assertExitCode(0);
    }

    /**
     * Reset password with parameter test, given password.
     * @test
     */
    function resetPasswordWithParameterGivenPassword()
    {
        $newPassword = 'secret';

        $this->artisan('user:reset-password ' . $this->existingUser->id)
            ->expectsOutput('Reset Password')
            ->expectsOutput('========================================')
            ->expectsOutput('ID: ' . $this->existingUser->id)
            ->expectsOutput('Name: ' . $this->existingUser->name)
            ->expectsOutput('Email: ' . $this->existingUser->email)
            ->expectsOutput('========================================')
            ->expectsQuestion('Enter new password or leave it blank if you want to generate random password: ', $newPassword)
            ->expectsQuestion('Are you sure you want to reset password for this user ?', 'y')
            ->expectsOutput('Reset password success.')
            ->assertExitCode(0);

        $user = User::find($this->existingUser->id);

        $this->assertTrue(Hash::check($newPassword, $user->password));
    }

    /**
     * Reset password with parameter test, random password.
     * @test
     */
    function resetPasswordWithParameterRandomPassword()
    {
        $this->artisan('user:reset-password ' . $this->existingUser->id)
            ->expectsOutput('Reset Password')
            ->expectsOutput('========================================')
            ->expectsOutput('ID: ' . $this->existingUser->id)
            ->expectsOutput('Name: ' . $this->existingUser->name)
            ->expectsOutput('Email: ' . $this->existingUser->email)
            ->expectsOutput('========================================')
            ->expectsQuestion('Enter new password or leave it blank if you want to generate random password: ', '')
            ->expectsQuestion('Are you sure you want to reset password for this user ?', 'y')
            ->expectsOutput('Reset password success.')
            ->assertExitCode(0);
    }

    /**
     * Reset password with input test, Cancelled
     * @test
     */
    function resetPasswordWithInputIdCancel()
    {
        $this->artisan('user:reset-password')
            ->expectsOutput('Reset Password')
            ->expectsOutput('========================================')
            ->expectsQuestion('Enter user\'s id or email: ', $this->existingUser->id)
            ->expectsOutput('========================================')
            ->expectsOutput('ID: ' . $this->existingUser->id)
            ->expectsOutput('Name: ' . $this->existingUser->name)
            ->expectsOutput('Email: ' . $this->existingUser->email)
            ->expectsOutput('========================================')
            ->expectsQuestion('Enter new password or leave it blank if you want to generate random password: ', '')
            ->expectsQuestion('Are you sure you want to reset password for this user ?', 'n')
            ->expectsOutput('Aborting')
            ->assertExitCode(0);
    }

    /**
     * Reset password with input test, given password.
     * @test
     */
    function resetPasswordWithInputGivenPassword()
    {
        $newPassword = 'secret';

        $this->artisan('user:reset-password')
            ->expectsOutput('Reset Password')
            ->expectsOutput('========================================')
            ->expectsQuestion('Enter user\'s id or email: ', $this->existingUser->id)
            ->expectsOutput('========================================')
            ->expectsOutput('ID: ' . $this->existingUser->id)
            ->expectsOutput('Name: ' . $this->existingUser->name)
            ->expectsOutput('Email: ' . $this->existingUser->email)
            ->expectsOutput('========================================')
            ->expectsQuestion('Enter new password or leave it blank if you want to generate random password: ', $newPassword)
            ->expectsQuestion('Are you sure you want to reset password for this user ?', 'y')
            ->expectsOutput('Reset password success.')
            ->assertExitCode(0);

        $user = User::find($this->existingUser->id);

        $this->assertTrue(Hash::check($newPassword, $user->password));
    }

    /**
     * Reset password with input test, random password.
     * @test
     */
    function resetPasswordWithInputRandomPassword()
    {
        $this->artisan('user:reset-password')
            ->expectsOutput('Reset Password')
            ->expectsOutput('========================================')
            ->expectsQuestion('Enter user\'s id or email: ', $this->existingUser->id)
            ->expectsOutput('========================================')
            ->expectsOutput('ID: ' . $this->existingUser->id)
            ->expectsOutput('Name: ' . $this->existingUser->name)
            ->expectsOutput('Email: ' . $this->existingUser->email)
            ->expectsOutput('========================================')
            ->expectsQuestion('Enter new password or leave it blank if you want to generate random password: ', '')
            ->expectsQuestion('Are you sure you want to reset password for this user ?', 'y')
            ->expectsOutput('Reset password success.')
            ->assertExitCode(0);
    }
}
