<?php

namespace Kusmayadi\ArtisanUser\Console\Commands;

use Illuminate\Console\Command;
use Kusmayadi\ArtisanUser\Models\User;

class UserEdit extends Command
{
    protected $signature = "user:edit {idOrEmail?}";
    protected $description = "List users";

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $idOrEmail = $this->argument('idOrEmail');

        if ($idOrEmail)
        {
            $this->handleArgument($idOrEmail);
        } else {
            $this->handleInput();
        }
    }

    private function handleArgument($idOrEmail)
    {
        $user = $this->getUser($idOrEmail);

        if (! $user)
        {
            $this->showError($idOrEmail);
        } else {
            $this->info('Edit User');
            $this->info('========================================');
            $this->showEdit($user);
        }
    }

    private function handleInput()
    {
        $this->info('Edit User');
        $this->info('========================================');
        $idOrEmail = $this->ask('Enter user\'s id or email: ');

        $user = $this->getUser($idOrEmail);

        if (! $user)
        {
            $this->showError($idOrEmail);
        } else {
            $this->info('========================================');
            $this->showEdit($user);
        }
    }

    private function getUser($idOrEmail)
    {
        if (is_numeric($idOrEmail)) {
            return $user = $this->getUserById($idOrEmail);
        } else {
            return $user = $this->getUserByEmail($idOrEmail);
        }
    }

    private function getUserById($id)
    {
        return User::where('id', $id)->first();
    }

    private function getUserByEmail($email)
    {
        return User::where('email', $email)->first();
    }

    private function showEdit($user)
    {
        $this->info('ID: ' . $user->id);
        $this->info('Name: ' . $user->name);
        $this->info('Email: ' . $user->email);
        $this->info('========================================');
        $this->info('Leave it blank if you don\'t want to change it.');

        $name = $this->ask('Name (' . $user->name  .'): ');
        $email = $this->ask('Email (' . $user->email . '): ');

        $this->info('========================================');
        $this->info('This following user\'s data will be saved: ');
        $this->info('Name: ' . ($name ? $name : $user->name));
        $this->info('Email: ' . ($email ? $email : $user->email));
        $this->info('========================================');

        $confirmation = strtolower($this->confirm('Do you wish to continue? (Y/N)'));

        if ($confirmation == 'y' OR $confirmation == 'yes' OR $confirmation == 1) {
            try {
                if ($name)
                    $user->name = $name;

                if ($email)
                    $user->email = $email;

                $user->save();

                $this->info('Saved.');
            } catch (Illuminate\Database\QueryException $e) {
                $this->error($e->getMessage());
            } catch (Exception $e) {
                $this->error($e->getMessage());
            }
        } else {
            $this->error('Aborting');
        }
    }

    private function showError($idOrEmail)
    {
        $idOrEmailLabel = is_numeric($idOrEmail) ? 'id' : 'email';

        $this->error('User with ' . $idOrEmailLabel . ' ' . $idOrEmail . ' is not exists.');
        $this->info('Run \'php artisan user:list\' to see list of users with their ' . $idOrEmailLabel . 's.');
    }
}
