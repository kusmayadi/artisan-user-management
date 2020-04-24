<?php

namespace Kusmayadi\ArtisanUser\Console\Commands;

use Illuminate\Console\Command;
use Kusmayadi\ArtisanUser\Console\Helpers\UserHelper;
use Kusmayadi\ArtisanUser\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class ResetPassword extends Command
{
    protected $signature = "user:reset-password {idOrEmail?}";
    protected $description = "Reset password";

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $idOrEmail = $this->argument('idOrEmail');

        $this->info('Reset Password');
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
            $this->resetPassword($user);
        }
    }

    private function handleInput()
    {
        $idOrEmail = $this->ask('Enter user\'s id or email: ');

        $user = UserHelper::getUser($idOrEmail);

        if (! $user)
        {
            $this->error(UserHelper::noUserMessage($idOrEmail));
        } else {
            $this->info('========================================');
            $this->resetPassword($user);
        }
    }

    private function resetPassword($user)
    {
        $this->info('ID: ' . $user->id);
        $this->info('Name: ' . $user->name);
        $this->info('Email: ' . $user->email);
        $this->info('========================================');
        $newPassword = $this->ask('Enter new password or leave it blank if you want to generate random password: ');

        $confirmation = strtolower($this->confirm('Are you sure you want to reset password for this user ?'));

        if ($confirmation == 'y' OR $confirmation == 'yes' OR $confirmation == 1) {
            try {
                if ($newPassword == '') {
                    $newPassword = Str::random(13);
                    $this->info('New password: ' . $newPassword);
                }

                $user->password = Hash::make($newPassword);
                $user->save();

                $this->info('Reset password success.');
            } catch (Illuminate\Database\QueryException $e) {
                $this->error($e->getMessage());
            } catch (Exception $e) {
                $this->error($e->getMessage());
            }
        } else {
            $this->error('Aborting');
        }
    }
}
