<?php

namespace Kusmayadi\ArtisanUser;

use Kusmayadi\ArtisanUser\Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use Kusmayadi\ArtisanUser\Models\User;

class EditUserTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->faker = \Faker\Factory::create();
        $this->inexistsUser = factory(User::class)->make(['id' => $this->faker->numberBetween(100, 1000)]);
        $this->existingUser = factory(User::class)->create();
        $this->editUser = factory(User::class)->make();
    }

    /**
     * Edit user test with wrong parameter id
     * @test
     */
    function editUserWrongParameterId()
    {
        $this->artisan('user:edit ' . $this->inexistsUser->id)
            ->expectsOutput('User with id ' . $this->inexistsUser->id . ' is not exists.')
            ->expectsOutput('Run \'php artisan user:list\' to see list of users with their ids.')
            ->assertExitCode(0);
    }

    /**
     * Edit user test with wrong parameter email
     * @test
     */
    function editUserWrongParameterEmail()
    {
        $this->artisan('user:edit ' . $this->inexistsUser->email)
            ->expectsOutput('User with email ' . $this->inexistsUser->email . ' is not exists.')
            ->expectsOutput('Run \'php artisan user:list\' to see list of users with their emails.')
            ->assertExitCode(0);
    }

    /**
     * Edit user test with wrong input id
     * @test
     */
    function editUserWrongInputId()
    {
        $this->artisan('user:edit')
            ->expectsOutput('Edit User')
            ->expectsOutput('========================================')
            ->expectsQuestion('Enter user\'s id or email: ', $this->inexistsUser->id)
            ->expectsOutput('User with id ' . $this->inexistsUser->id . ' is not exists.')
            ->expectsOutput('Run \'php artisan user:list\' to see list of users with their ids.')
            ->assertExitCode(0);
    }

    /**
     * Edit user test with wrong input email
     * @test
     */
    function editUserWrongInputEmail()
    {
        $this->artisan('user:edit')
            ->expectsOutput('Edit User')
            ->expectsOutput('========================================')
            ->expectsQuestion('Enter user\'s id or email: ', $this->inexistsUser->email)
            ->expectsOutput('User with email ' . $this->inexistsUser->email . ' is not exists.')
            ->expectsOutput('Run \'php artisan user:list\' to see list of users with their emails.')
            ->assertExitCode(0);
    }

    /**
     * Edit user test with parameter id, only name will be changed
     * @test
     */
    function editUserWithParameterIdOnlyName()
    {
        $this->artisan('user:edit ' . $this->existingUser->id)
            ->expectsOutput('Edit User')
            ->expectsOutput('========================================')
            ->expectsOutput('ID: ' . $this->existingUser->id)
            ->expectsOutput('Name: ' . $this->existingUser->name)
            ->expectsOutput('Email: ' . $this->existingUser->email)
            ->expectsOutput('========================================')
            ->expectsOutput('Leave it blank if you don\'t want to change it.')
            ->expectsQuestion('Name (' . $this->existingUser->name  .'): ', $this->editUser->name)
            ->expectsQuestion('Email (' . $this->existingUser->email . '): ', '')
            ->expectsOutput('========================================')
            ->expectsOutput('This following user\'s data will be saved: ')
            ->expectsOutput('Name: ' . $this->editUser->name)
            ->expectsOutput('Email: ' . $this->existingUser->email)
            ->expectsOutput('========================================')
            ->expectsQuestion('Do you wish to continue? (Y/N)', 'Y')
            ->expectsOutput('Saved.')
            ->assertExitCode(0);

        $this->assertDatabaseHas('users', ['name' => $this->editUser->name, 'email' => $this->existingUser->email]);
    }

    /**
     * Edit user test with parameter id, only ename will be changed but cancel the process
     * @test
     */
    function editUserWithParameterIdOnlyNameCancel()
    {
        $this->artisan('user:edit ' . $this->existingUser->id)
            ->expectsOutput('Edit User')
            ->expectsOutput('========================================')
            ->expectsOutput('ID: ' . $this->existingUser->id)
            ->expectsOutput('Name: ' . $this->existingUser->name)
            ->expectsOutput('Email: ' . $this->existingUser->email)
            ->expectsOutput('========================================')
            ->expectsOutput('Leave it blank if you don\'t want to change it.')
            ->expectsQuestion('Name (' . $this->existingUser->name  .'): ', $this->editUser->name)
            ->expectsQuestion('Email (' . $this->existingUser->email . '): ', '')
            ->expectsOutput('========================================')
            ->expectsOutput('This following user\'s data will be saved: ')
            ->expectsOutput('Name: ' . $this->editUser->name)
            ->expectsOutput('Email: ' . $this->existingUser->email)
            ->expectsOutput('========================================')
            ->expectsQuestion('Do you wish to continue? (Y/N)', 'N')
            ->expectsOutput('Aborting')
            ->assertExitCode(0);

        $this->assertDatabaseMissing('users', ['name' => $this->editUser->name]);
    }

    /**
     * Edit user test with parameter id, only email will be changed
     * @test
     */
    function editUserWithParameterIdOnlyEmail()
    {
        $this->artisan('user:edit ' . $this->existingUser->id)
            ->expectsOutput('Edit User')
            ->expectsOutput('========================================')
            ->expectsOutput('ID: ' . $this->existingUser->id)
            ->expectsOutput('Name: ' . $this->existingUser->name)
            ->expectsOutput('Email: ' . $this->existingUser->email)
            ->expectsOutput('========================================')
            ->expectsOutput('Leave it blank if you don\'t want to change it.')
            ->expectsQuestion('Name (' . $this->existingUser->name  .'): ', '')
            ->expectsQuestion('Email (' . $this->existingUser->email . '): ', $this->editUser->email)
            ->expectsOutput('========================================')
            ->expectsOutput('This following user\'s data will be saved: ')
            ->expectsOutput('Name: ' . $this->existingUser->name)
            ->expectsOutput('Email: ' . $this->editUser->email)
            ->expectsOutput('========================================')
            ->expectsQuestion('Do you wish to continue? (Y/N)', 'Y')
            ->expectsOutput('Saved.')
            ->assertExitCode(0);

        $this->assertDatabaseHas('users', ['name' => $this->existingUser->name, 'email' => $this->editUser->email]);
    }

    /**
     * Edit user test with parameter id, only email will be changed but cancel the process
     * @test
     */
    function editUserWithParameterIdOnlyEmailCancel()
    {
        $this->artisan('user:edit ' . $this->existingUser->id)
            ->expectsOutput('Edit User')
            ->expectsOutput('========================================')
            ->expectsOutput('ID: ' . $this->existingUser->id)
            ->expectsOutput('Name: ' . $this->existingUser->name)
            ->expectsOutput('Email: ' . $this->existingUser->email)
            ->expectsOutput('========================================')
            ->expectsOutput('Leave it blank if you don\'t want to change it.')
            ->expectsQuestion('Name (' . $this->existingUser->name  .'): ', '')
            ->expectsQuestion('Email (' . $this->existingUser->email . '): ', $this->editUser->email)
            ->expectsOutput('========================================')
            ->expectsOutput('This following user\'s data will be saved: ')
            ->expectsOutput('Name: ' . $this->existingUser->name)
            ->expectsOutput('Email: ' . $this->editUser->email)
            ->expectsOutput('========================================')
            ->expectsQuestion('Do you wish to continue? (Y/N)', 'N')
            ->expectsOutput('Aborting')
            ->assertExitCode(0);

        $this->assertDatabaseMissing('users', ['email' => $this->editUser->email]);
    }

    /**
     * Edit user test with parameter id
     * @test
     */
    function editUserWithParameterId()
    {
        $this->artisan('user:edit ' . $this->existingUser->id)
            ->expectsOutput('Edit User')
            ->expectsOutput('========================================')
            ->expectsOutput('ID: ' . $this->existingUser->id)
            ->expectsOutput('Name: ' . $this->existingUser->name)
            ->expectsOutput('Email: ' . $this->existingUser->email)
            ->expectsOutput('========================================')
            ->expectsOutput('Leave it blank if you don\'t want to change it.')
            ->expectsQuestion('Name (' . $this->existingUser->name  .'): ', $this->editUser->name)
            ->expectsQuestion('Email (' . $this->existingUser->email . '): ', $this->editUser->email)
            ->expectsOutput('========================================')
            ->expectsOutput('This following user\'s data will be saved: ')
            ->expectsOutput('Name: ' . $this->editUser->name)
            ->expectsOutput('Email: ' . $this->editUser->email)
            ->expectsOutput('========================================')
            ->expectsQuestion('Do you wish to continue? (Y/N)', 'Y')
            ->expectsOutput('Saved.')
            ->assertExitCode(0);

        $this->assertDatabaseHas('users', ['name' => $this->editUser->name, 'email' => $this->editUser->email]);
    }

    /**
     * Edit user test with parameter id and cancel the process
     * @test
     */
    function editUserWithParameterIdCancel()
    {
        $this->artisan('user:edit ' . $this->existingUser->id)
            ->expectsOutput('Edit User')
            ->expectsOutput('========================================')
            ->expectsOutput('ID: ' . $this->existingUser->id)
            ->expectsOutput('Name: ' . $this->existingUser->name)
            ->expectsOutput('Email: ' . $this->existingUser->email)
            ->expectsOutput('========================================')
            ->expectsOutput('Leave it blank if you don\'t want to change it.')
            ->expectsQuestion('Name (' . $this->existingUser->name  .'): ', $this->editUser->name)
            ->expectsQuestion('Email (' . $this->existingUser->email . '): ', $this->editUser->email)
            ->expectsOutput('========================================')
            ->expectsOutput('This following user\'s data will be saved: ')
            ->expectsOutput('Name: ' . $this->editUser->name)
            ->expectsOutput('Email: ' . $this->editUser->email)
            ->expectsOutput('========================================')
            ->expectsQuestion('Do you wish to continue? (Y/N)', 'N')
            ->expectsOutput('Aborting')
            ->assertExitCode(0);

        $this->assertDatabaseMissing('users', ['name' => $this->editUser->name, 'email' => $this->editUser->email]);
    }

    /**
     * Edit user test with parameter email, only name will be changed
     * @test
     */
    function editUserWithParameterEmailOnlyName()
    {
        $this->artisan('user:edit ' . $this->existingUser->email)
            ->expectsOutput('Edit User')
            ->expectsOutput('========================================')
            ->expectsOutput('ID: ' . $this->existingUser->id)
            ->expectsOutput('Name: ' . $this->existingUser->name)
            ->expectsOutput('Email: ' . $this->existingUser->email)
            ->expectsOutput('========================================')
            ->expectsOutput('Leave it blank if you don\'t want to change it.')
            ->expectsQuestion('Name (' . $this->existingUser->name  .'): ', $this->editUser->name)
            ->expectsQuestion('Email (' . $this->existingUser->email . '): ', '')
            ->expectsOutput('========================================')
            ->expectsOutput('This following user\'s data will be saved: ')
            ->expectsOutput('Name: ' . $this->editUser->name)
            ->expectsOutput('Email: ' . $this->existingUser->email)
            ->expectsOutput('========================================')
            ->expectsQuestion('Do you wish to continue? (Y/N)', 'Y')
            ->expectsOutput('Saved.')
            ->assertExitCode(0);

        $this->assertDatabaseHas('users', ['name' => $this->editUser->name, 'email' => $this->existingUser->email]);
    }

    /**
     * Edit user test with parameter email, only name will be changed but cancel the process
     * @test
     */
    function editUserWithParameterEmailOnlyNameCancel()
    {
        $this->artisan('user:edit ' . $this->existingUser->email)
            ->expectsOutput('Edit User')
            ->expectsOutput('========================================')
            ->expectsOutput('ID: ' . $this->existingUser->id)
            ->expectsOutput('Name: ' . $this->existingUser->name)
            ->expectsOutput('Email: ' . $this->existingUser->email)
            ->expectsOutput('========================================')
            ->expectsOutput('Leave it blank if you don\'t want to change it.')
            ->expectsQuestion('Name (' . $this->existingUser->name  .'): ', $this->editUser->name)
            ->expectsQuestion('Email (' . $this->existingUser->email . '): ', '')
            ->expectsOutput('========================================')
            ->expectsOutput('This following user\'s data will be saved: ')
            ->expectsOutput('Name: ' . $this->editUser->name)
            ->expectsOutput('Email: ' . $this->existingUser->email)
            ->expectsOutput('========================================')
            ->expectsQuestion('Do you wish to continue? (Y/N)', 'N')
            ->expectsOutput('Aborting')
            ->assertExitCode(0);

        $this->assertDatabaseMissing('users', ['name' => $this->editUser->name]);
    }

    /**
     * Edit user test with parameter email, only email will be changed
     * @test
     */
    function editUserWithParameterEmailOnlyEmail()
    {
        $this->artisan('user:edit ' . $this->existingUser->email)
            ->expectsOutput('Edit User')
            ->expectsOutput('========================================')
            ->expectsOutput('ID: ' . $this->existingUser->id)
            ->expectsOutput('Name: ' . $this->existingUser->name)
            ->expectsOutput('Email: ' . $this->existingUser->email)
            ->expectsOutput('========================================')
            ->expectsOutput('Leave it blank if you don\'t want to change it.')
            ->expectsQuestion('Name (' . $this->existingUser->name  .'): ', '')
            ->expectsQuestion('Email (' . $this->existingUser->email . '): ', $this->editUser->email)
            ->expectsOutput('========================================')
            ->expectsOutput('This following user\'s data will be saved: ')
            ->expectsOutput('Name: ' . $this->existingUser->name)
            ->expectsOutput('Email: ' . $this->editUser->email)
            ->expectsOutput('========================================')
            ->expectsQuestion('Do you wish to continue? (Y/N)', 'Y')
            ->expectsOutput('Saved.')
            ->assertExitCode(0);

        $this->assertDatabaseHas('users', ['name' => $this->existingUser->name, 'email' => $this->editUser->email]);
    }

    /**
     * Edit user test with parameter email, only email will be changed but cancel the process
     * @test
     */
    function editUserWithParameterEmailOnlyEmailCancel()
    {
        $this->artisan('user:edit ' . $this->existingUser->email)
            ->expectsOutput('Edit User')
            ->expectsOutput('========================================')
            ->expectsOutput('ID: ' . $this->existingUser->id)
            ->expectsOutput('Name: ' . $this->existingUser->name)
            ->expectsOutput('Email: ' . $this->existingUser->email)
            ->expectsOutput('========================================')
            ->expectsOutput('Leave it blank if you don\'t want to change it.')
            ->expectsQuestion('Name (' . $this->existingUser->name  .'): ', '')
            ->expectsQuestion('Email (' . $this->existingUser->email . '): ', $this->editUser->email)
            ->expectsOutput('========================================')
            ->expectsOutput('This following user\'s data will be saved: ')
            ->expectsOutput('Name: ' . $this->existingUser->name)
            ->expectsOutput('Email: ' . $this->editUser->email)
            ->expectsOutput('========================================')
            ->expectsQuestion('Do you wish to continue? (Y/N)', 'N')
            ->expectsOutput('Aborting')
            ->assertExitCode(0);

        $this->assertDatabaseMissing('users', ['email' => $this->editUser->email]);
    }

    /**
     * Edit user test with parameter email
     * @test
     */
    function editUserWithParameterEmail()
    {
        $this->artisan('user:edit ' . $this->existingUser->email)
            ->expectsOutput('Edit User')
            ->expectsOutput('========================================')
            ->expectsOutput('ID: ' . $this->existingUser->id)
            ->expectsOutput('Name: ' . $this->existingUser->name)
            ->expectsOutput('Email: ' . $this->existingUser->email)
            ->expectsOutput('========================================')
            ->expectsOutput('Leave it blank if you don\'t want to change it.')
            ->expectsQuestion('Name (' . $this->existingUser->name  .'): ', $this->editUser->name)
            ->expectsQuestion('Email (' . $this->existingUser->email . '): ', $this->editUser->email)
            ->expectsOutput('========================================')
            ->expectsOutput('This following user\'s data will be saved: ')
            ->expectsOutput('Name: ' . $this->editUser->name)
            ->expectsOutput('Email: ' . $this->editUser->email)
            ->expectsOutput('========================================')
            ->expectsQuestion('Do you wish to continue? (Y/N)', 'Y')
            ->expectsOutput('Saved.')
            ->assertExitCode(0);

        $this->assertDatabaseHas('users', ['name' => $this->editUser->name, 'email' => $this->editUser->email]);
    }

    /**
     * Edit user test with parameter email and cancel the process
     * @test
     */
    function editUserWithParameterEmailCancel()
    {
        $this->artisan('user:edit ' . $this->existingUser->email)
            ->expectsOutput('Edit User')
            ->expectsOutput('========================================')
            ->expectsOutput('ID: ' . $this->existingUser->id)
            ->expectsOutput('Name: ' . $this->existingUser->name)
            ->expectsOutput('Email: ' . $this->existingUser->email)
            ->expectsOutput('========================================')
            ->expectsOutput('Leave it blank if you don\'t want to change it.')
            ->expectsQuestion('Name (' . $this->existingUser->name  .'): ', $this->editUser->name)
            ->expectsQuestion('Email (' . $this->existingUser->email . '): ', $this->editUser->email)
            ->expectsOutput('========================================')
            ->expectsOutput('This following user\'s data will be saved: ')
            ->expectsOutput('Name: ' . $this->editUser->name)
            ->expectsOutput('Email: ' . $this->editUser->email)
            ->expectsOutput('========================================')
            ->expectsQuestion('Do you wish to continue? (Y/N)', 'N')
            ->expectsOutput('Aborting')
            ->assertExitCode(0);

        $this->assertDatabaseMissing('users', ['name' => $this->editUser->name, 'email' => $this->editUser->email]);
    }

    /**
     * Edit user test with input id only name will be changed
     * @test
     */
    function editUserWithInputIdOnlyName()
    {
        $this->artisan('user:edit')
            ->expectsOutput('Edit User')
            ->expectsOutput('========================================')
            ->expectsQuestion('Enter user\'s id or email: ', $this->existingUser->id)
            ->expectsOutput('========================================')
            ->expectsOutput('ID: ' . $this->existingUser->id)
            ->expectsOutput('Name: ' . $this->existingUser->name)
            ->expectsOutput('Email: ' . $this->existingUser->email)
            ->expectsOutput('========================================')
            ->expectsOutput('Leave it blank if you don\'t want to change it.')
            ->expectsQuestion('Name (' . $this->existingUser->name  .'): ', $this->editUser->name)
            ->expectsQuestion('Email (' . $this->existingUser->email . '): ', '')
            ->expectsOutput('========================================')
            ->expectsOutput('This following user\'s data will be saved: ')
            ->expectsOutput('Name: ' . $this->editUser->name)
            ->expectsOutput('Email: ' . $this->existingUser->email)
            ->expectsOutput('========================================')
            ->expectsQuestion('Do you wish to continue? (Y/N)', 'Y')
            ->expectsOutput('Saved.')
            ->assertExitCode(0);

        $this->assertDatabaseHas('users', ['name' => $this->editUser->name, 'email' => $this->existingUser->email]);
    }

    /**
     * Edit user test with input id only name will be changed but cancel the process
     * @test
     */
    function editUserWithInputIdOnlyNameCancel()
    {
        $this->artisan('user:edit')
            ->expectsOutput('Edit User')
            ->expectsOutput('========================================')
            ->expectsQuestion('Enter user\'s id or email: ', $this->existingUser->id)
            ->expectsOutput('========================================')
            ->expectsOutput('ID: ' . $this->existingUser->id)
            ->expectsOutput('Name: ' . $this->existingUser->name)
            ->expectsOutput('Email: ' . $this->existingUser->email)
            ->expectsOutput('========================================')
            ->expectsOutput('Leave it blank if you don\'t want to change it.')
            ->expectsQuestion('Name (' . $this->existingUser->name  .'): ', $this->editUser->name)
            ->expectsQuestion('Email (' . $this->existingUser->email . '): ', '')
            ->expectsOutput('========================================')
            ->expectsOutput('This following user\'s data will be saved: ')
            ->expectsOutput('Name: ' . $this->editUser->name)
            ->expectsOutput('Email: ' . $this->existingUser->email)
            ->expectsOutput('========================================')
            ->expectsQuestion('Do you wish to continue? (Y/N)', 'N')
            ->expectsOutput('Aborting')
            ->assertExitCode(0);

        $this->assertDatabaseMissing('users', ['name' => $this->editUser->name]);
    }

    /**
     * Edit user test with input id only email will be changed
     * @test
     */
    function editUserWithInputIdOnlyEmail()
    {
        $this->artisan('user:edit')
            ->expectsOutput('Edit User')
            ->expectsOutput('========================================')
            ->expectsQuestion('Enter user\'s id or email: ', $this->existingUser->id)
            ->expectsOutput('========================================')
            ->expectsOutput('ID: ' . $this->existingUser->id)
            ->expectsOutput('Name: ' . $this->existingUser->name)
            ->expectsOutput('Email: ' . $this->existingUser->email)
            ->expectsOutput('========================================')
            ->expectsOutput('Leave it blank if you don\'t want to change it.')
            ->expectsQuestion('Name (' . $this->existingUser->name  .'): ', '')
            ->expectsQuestion('Email (' . $this->existingUser->email . '): ', $this->editUser->email)
            ->expectsOutput('========================================')
            ->expectsOutput('This following user\'s data will be saved: ')
            ->expectsOutput('Name: ' . $this->existingUser->name)
            ->expectsOutput('Email: ' . $this->editUser->email)
            ->expectsOutput('========================================')
            ->expectsQuestion('Do you wish to continue? (Y/N)', 'Y')
            ->expectsOutput('Saved.')
            ->assertExitCode(0);

        $this->assertDatabaseHas('users', ['name' => $this->existingUser->name, 'email' => $this->editUser->email]);
    }

    /**
     * Edit user test with input id only email will be changed but cancel the process
     * @test
     */
    function editUserWithInputIdOnlyEmailCancel()
    {
        $this->artisan('user:edit')
            ->expectsOutput('Edit User')
            ->expectsOutput('========================================')
            ->expectsQuestion('Enter user\'s id or email: ', $this->existingUser->id)
            ->expectsOutput('========================================')
            ->expectsOutput('ID: ' . $this->existingUser->id)
            ->expectsOutput('Name: ' . $this->existingUser->name)
            ->expectsOutput('Email: ' . $this->existingUser->email)
            ->expectsOutput('========================================')
            ->expectsOutput('Leave it blank if you don\'t want to change it.')
            ->expectsQuestion('Name (' . $this->existingUser->name  .'): ', '')
            ->expectsQuestion('Email (' . $this->existingUser->email . '): ', $this->editUser->email)
            ->expectsOutput('========================================')
            ->expectsOutput('This following user\'s data will be saved: ')
            ->expectsOutput('Name: ' . $this->existingUser->name)
            ->expectsOutput('Email: ' . $this->editUser->email)
            ->expectsOutput('========================================')
            ->expectsQuestion('Do you wish to continue? (Y/N)', 'N')
            ->expectsOutput('Aborting')
            ->assertExitCode(0);

        $this->assertDatabaseMissing('users', ['email' => $this->editUser->email]);
    }

    /**
     * Edit user test with input id
     * @test
     */
    function editUserWithInputId()
    {
        $this->artisan('user:edit')
            ->expectsOutput('Edit User')
            ->expectsOutput('========================================')
            ->expectsQuestion('Enter user\'s id or email: ', $this->existingUser->id)
            ->expectsOutput('========================================')
            ->expectsOutput('ID: ' . $this->existingUser->id)
            ->expectsOutput('Name: ' . $this->existingUser->name)
            ->expectsOutput('Email: ' . $this->existingUser->email)
            ->expectsOutput('========================================')
            ->expectsOutput('Leave it blank if you don\'t want to change it.')
            ->expectsQuestion('Name (' . $this->existingUser->name  .'): ', $this->editUser->name)
            ->expectsQuestion('Email (' . $this->existingUser->email . '): ', $this->editUser->email)
            ->expectsOutput('========================================')
            ->expectsOutput('This following user\'s data will be saved: ')
            ->expectsOutput('Name: ' . $this->editUser->name)
            ->expectsOutput('Email: ' . $this->editUser->email)
            ->expectsOutput('========================================')
            ->expectsQuestion('Do you wish to continue? (Y/N)', 'Y')
            ->expectsOutput('Saved.')
            ->assertExitCode(0);

        $this->assertDatabaseHas('users', ['name' => $this->editUser->name, 'email' => $this->editUser->email]);
    }

    /**
     * Edit user test with input id and cancel the process
     * @test
     */
    function editUserWithInputIdCancel()
    {
        $this->artisan('user:edit')
            ->expectsOutput('Edit User')
            ->expectsOutput('========================================')
            ->expectsQuestion('Enter user\'s id or email: ', $this->existingUser->id)
            ->expectsOutput('========================================')
            ->expectsOutput('ID: ' . $this->existingUser->id)
            ->expectsOutput('Name: ' . $this->existingUser->name)
            ->expectsOutput('Email: ' . $this->existingUser->email)
            ->expectsOutput('========================================')
            ->expectsOutput('Leave it blank if you don\'t want to change it.')
            ->expectsQuestion('Name (' . $this->existingUser->name  .'): ', $this->editUser->name)
            ->expectsQuestion('Email (' . $this->existingUser->email . '): ', $this->editUser->email)
            ->expectsOutput('========================================')
            ->expectsOutput('This following user\'s data will be saved: ')
            ->expectsOutput('Name: ' . $this->editUser->name)
            ->expectsOutput('Email: ' . $this->editUser->email)
            ->expectsOutput('========================================')
            ->expectsQuestion('Do you wish to continue? (Y/N)', 'N')
            ->expectsOutput('Aborting')
            ->assertExitCode(0);

        $this->assertDatabaseMissing('users', ['name' => $this->editUser->name, 'email' => $this->editUser->email]);
    }

    /**
     * Edit user test with input email only name will be changed
     * @test
     */
    function editUserWithInputEmailOnlyName()
    {
        $this->artisan('user:edit')
            ->expectsOutput('Edit User')
            ->expectsOutput('========================================')
            ->expectsQuestion('Enter user\'s id or email: ', $this->existingUser->email)
            ->expectsOutput('========================================')
            ->expectsOutput('ID: ' . $this->existingUser->id)
            ->expectsOutput('Name: ' . $this->existingUser->name)
            ->expectsOutput('Email: ' . $this->existingUser->email)
            ->expectsOutput('========================================')
            ->expectsOutput('Leave it blank if you don\'t want to change it.')
            ->expectsQuestion('Name (' . $this->existingUser->name  .'): ', $this->editUser->name)
            ->expectsQuestion('Email (' . $this->existingUser->email . '): ', '')
            ->expectsOutput('========================================')
            ->expectsOutput('This following user\'s data will be saved: ')
            ->expectsOutput('Name: ' . $this->editUser->name)
            ->expectsOutput('Email: ' . $this->existingUser->email)
            ->expectsOutput('========================================')
            ->expectsQuestion('Do you wish to continue? (Y/N)', 'Y')
            ->expectsOutput('Saved.')
            ->assertExitCode(0);

        $this->assertDatabaseHas('users', ['name' => $this->editUser->name, 'email' => $this->existingUser->email]);
    }

    /**
     * Edit user test with input email only name will be changed but cancel the process
     * @test
     */
    function editUserWithInputEmailOnlyNameCancel()
    {
        $this->artisan('user:edit')
            ->expectsOutput('Edit User')
            ->expectsOutput('========================================')
            ->expectsQuestion('Enter user\'s id or email: ', $this->existingUser->email)
            ->expectsOutput('========================================')
            ->expectsOutput('ID: ' . $this->existingUser->id)
            ->expectsOutput('Name: ' . $this->existingUser->name)
            ->expectsOutput('Email: ' . $this->existingUser->email)
            ->expectsOutput('========================================')
            ->expectsOutput('Leave it blank if you don\'t want to change it.')
            ->expectsQuestion('Name (' . $this->existingUser->name  .'): ', $this->editUser->name)
            ->expectsQuestion('Email (' . $this->existingUser->email . '): ', '')
            ->expectsOutput('========================================')
            ->expectsOutput('This following user\'s data will be saved: ')
            ->expectsOutput('Name: ' . $this->editUser->name)
            ->expectsOutput('Email: ' . $this->existingUser->email)
            ->expectsOutput('========================================')
            ->expectsQuestion('Do you wish to continue? (Y/N)', 'N')
            ->expectsOutput('Aborting')
            ->assertExitCode(0);

        $this->assertDatabaseMissing('users', ['name' => $this->editUser->name]);
    }

    /**
     * Edit user test with input email only email will be changed
     * @test
     */
    function editUserWithInputEmailOnlyEmail()
    {
        $this->artisan('user:edit')
            ->expectsOutput('Edit User')
            ->expectsOutput('========================================')
            ->expectsQuestion('Enter user\'s id or email: ', $this->existingUser->email)
            ->expectsOutput('========================================')
            ->expectsOutput('ID: ' . $this->existingUser->id)
            ->expectsOutput('Name: ' . $this->existingUser->name)
            ->expectsOutput('Email: ' . $this->existingUser->email)
            ->expectsOutput('========================================')
            ->expectsOutput('Leave it blank if you don\'t want to change it.')
            ->expectsQuestion('Name (' . $this->existingUser->name  .'): ', '')
            ->expectsQuestion('Email (' . $this->existingUser->email . '): ', $this->editUser->email)
            ->expectsOutput('========================================')
            ->expectsOutput('This following user\'s data will be saved: ')
            ->expectsOutput('Name: ' . $this->existingUser->name)
            ->expectsOutput('Email: ' . $this->editUser->email)
            ->expectsOutput('========================================')
            ->expectsQuestion('Do you wish to continue? (Y/N)', 'Y')
            ->expectsOutput('Saved.')
            ->assertExitCode(0);

        $this->assertDatabaseHas('users', ['name' => $this->existingUser->name, 'email' => $this->editUser->email]);
    }

    /**
     * Edit user test with input email only email will be changed but cancel the process
     * @test
     */
    function editUserWithInputEmailOnlyEmailCancel()
    {
        $this->artisan('user:edit')
            ->expectsOutput('Edit User')
            ->expectsOutput('========================================')
            ->expectsQuestion('Enter user\'s id or email: ', $this->existingUser->email)
            ->expectsOutput('========================================')
            ->expectsOutput('ID: ' . $this->existingUser->id)
            ->expectsOutput('Name: ' . $this->existingUser->name)
            ->expectsOutput('Email: ' . $this->existingUser->email)
            ->expectsOutput('========================================')
            ->expectsOutput('Leave it blank if you don\'t want to change it.')
            ->expectsQuestion('Name (' . $this->existingUser->name  .'): ', '')
            ->expectsQuestion('Email (' . $this->existingUser->email . '): ', $this->editUser->email)
            ->expectsOutput('========================================')
            ->expectsOutput('This following user\'s data will be saved: ')
            ->expectsOutput('Name: ' . $this->existingUser->name)
            ->expectsOutput('Email: ' . $this->editUser->email)
            ->expectsOutput('========================================')
            ->expectsQuestion('Do you wish to continue? (Y/N)', 'N')
            ->expectsOutput('Aborting')
            ->assertExitCode(0);

        $this->assertDatabaseMissing('users', ['email' => $this->editUser->email]);
    }

    /**
     * Edit user test with input email
     * @test
     */
    function editUserWithInputEmail()
    {
        $this->artisan('user:edit')
            ->expectsOutput('Edit User')
            ->expectsOutput('========================================')
            ->expectsQuestion('Enter user\'s id or email: ', $this->existingUser->email)
            ->expectsOutput('========================================')
            ->expectsOutput('ID: ' . $this->existingUser->id)
            ->expectsOutput('Name: ' . $this->existingUser->name)
            ->expectsOutput('Email: ' . $this->existingUser->email)
            ->expectsOutput('========================================')
            ->expectsOutput('Leave it blank if you don\'t want to change it.')
            ->expectsQuestion('Name (' . $this->existingUser->name  .'): ', $this->editUser->name)
            ->expectsQuestion('Email (' . $this->existingUser->email . '): ', $this->editUser->email)
            ->expectsOutput('========================================')
            ->expectsOutput('This following user\'s data will be saved: ')
            ->expectsOutput('Name: ' . $this->editUser->name)
            ->expectsOutput('Email: ' . $this->editUser->email)
            ->expectsOutput('========================================')
            ->expectsQuestion('Do you wish to continue? (Y/N)', 'Y')
            ->expectsOutput('Saved.')
            ->assertExitCode(0);

        $this->assertDatabaseHas('users', ['name' => $this->editUser->name, 'email' => $this->editUser->email]);
    }

    /**
     * Edit user test with input email and cancel the process
     * @test
     */
    function editUserWithInputEmailCancel()
    {
        $this->artisan('user:edit')
            ->expectsOutput('Edit User')
            ->expectsOutput('========================================')
            ->expectsQuestion('Enter user\'s id or email: ', $this->existingUser->email)
            ->expectsOutput('========================================')
            ->expectsOutput('ID: ' . $this->existingUser->id)
            ->expectsOutput('Name: ' . $this->existingUser->name)
            ->expectsOutput('Email: ' . $this->existingUser->email)
            ->expectsOutput('========================================')
            ->expectsOutput('Leave it blank if you don\'t want to change it.')
            ->expectsQuestion('Name (' . $this->existingUser->name  .'): ', $this->editUser->name)
            ->expectsQuestion('Email (' . $this->existingUser->email . '): ', $this->editUser->email)
            ->expectsOutput('========================================')
            ->expectsOutput('This following user\'s data will be saved: ')
            ->expectsOutput('Name: ' . $this->editUser->name)
            ->expectsOutput('Email: ' . $this->editUser->email)
            ->expectsOutput('========================================')
            ->expectsQuestion('Do you wish to continue? (Y/N)', 'N')
            ->expectsOutput('Aborting')
            ->assertExitCode(0);

        $this->assertDatabaseMissing('users', ['name' => $this->editUser->name, 'email' => $this->editUser->email]);
    }
}
