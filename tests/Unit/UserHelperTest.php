<?php

namespace Kusmayadi\ArtisanUser\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Kusmayadi\ArtisanUser\Tests\TestCase;
use Illuminate\Support\Str;
use Kusmayadi\ArtisanUser\Models\User;
use Kusmayadi\ArtisanUser\Console\Helpers\UserHelper;

class UserHelperTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Get user by id
     * @test
     */
    function getUserById()
    {
        $user = factory(User::class)->create();

        $this->assertInstanceOf(User::class, UserHelper::getUser($user->id));
    }

    /**
     * Get user by email
     * @test
     */
    function getUserByEmail()
    {
        $user = factory(User::class)->create();

        $this->assertInstanceOf(User::class, UserHelper::getUser($user->email));
    }

    /**
     * Get user by invalid id
     * @test
     */
    function getUserByInvalidId()
    {
        $this->assertNull(UserHelper::getUser(rand(999, 9999)));
    }

    /**
     * Get user by invalid email
     * @test
     */
    function getUserByInvalidEmail()
    {
        $user = factory(User::class)->make();

        $this->assertNull(UserHelper::getUser($user->email));
    }

    /**
     * Get user by invalid input
     * @test
     */
    function getUserByInvalidInput()
    {
        $this->assertNull(UserHelper::getUser(Str::random(125)));
    }

    /**
     * Return no user message with parameter id
     * @test
     */
    function noUserMessageById()
    {
        $message = UserHelper::noUserMessage(1);

        $this->assertEquals($message, "User with id 1 is not exists.\nRun 'php artisan user:list' to see list of users with their ids.");
    }

    /**
     * Return no user message with parameter email
     * @test
     */
    function noUserMessageByEmail()
    {
        $user = factory(User::class)->make();

        $message = UserHelper::noUserMessage($user->email);

        $this->assertEquals($message, "User with email " . $user->email . " is not exists.\nRun 'php artisan user:list' to see list of users with their emails.");
    }
}
