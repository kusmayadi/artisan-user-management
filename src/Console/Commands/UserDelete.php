<?php

namespace Kusmayadi\ArtisanUser\Console\Commands;

use Illuminate\Console\Command;
use Kusmayadi\ArtisanUser\Console\Helpers\UserHelper;
use Kusmayadi\ArtisanUser\Models\User;

class UserDelete extends Command
{
    protected $signature = "user:delete {idOrEmail?}";
    protected $description = "Delete user";

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $idOrEmail = $this->argument('idOrEmail');

        $this->info('Delete User');
        $this->info('========================================');

        if ($idOrEmail)
        {
            $this->handleArgument($idOrEmail);
        } else {
            $this->handleInput();
        }
    }

    private function handleArgument($idOrEmail)
    {
        $user = UserHelper::getUser($idOrEmail);

        if (! $user)
        {
            $this->error(UserHelper::noUserMessage($idOrEmail));
        } else {
            $this->delete($user);
        }
    }

    private function handleInput()
    {
        $idOrEmail = $this->ask('Enter user\'s id or email: ');
        $this->info('========================================');

        $user = UserHelper::getUser($idOrEmail);

        if (! $user)
        {
            $this->error(UserHelper::noUserMessage($idOrEmail));
        } else {
            $this->delete($user);
        }
    }

    private function delete($user)
    {
        $this->info('ID: ' . $user->id);
        $this->info('Name: ' . $user->name);
        $this->info('Email: ' . $user->email);
        $this->info('========================================');

        $confirmation = strtolower($this->confirm('Are you sure you want to delete this user?'));

        if ($confirmation == 'y' OR $confirmation == 'yes' OR $confirmation == 1) {
            try {
                $user->delete();

                $this->info('User has been remove from the system.');
            } catch (Illuminate\Database\QueryException $e) {
                $this->error($e->getMessage());
            } catch (Exception $e) {
                $this->error($e->getMessage());
            }
        } else {
            $this->error('Aborting.');
        }
    }
}
