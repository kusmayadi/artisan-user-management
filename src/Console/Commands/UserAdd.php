<?php

namespace Kusmayadi\ArtisanUser\Console\Commands;

use Illuminate\Console\Command;
use Kusmayadi\ArtisanUser\Models\User;

class UserAdd extends Command
{
    protected $signature = "user:add";
    protected $description = "Add user to the system";

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $input = [];

        $this->info('Add new user');
        $this->info('========================================');

        $input['name'] = $this->ask('Name: ');
        $input['email'] = $this->ask('Email: ');
        $input['password'] = $this->askPassword();

        $this->info('========================================');
        $this->info('This following user will be added to the system:');
        $this->info('Name: ' . $input['name']);
        $this->info('Email: ' . $input['email']);
        $this->info('========================================');

        $confirmation = strtolower($this->confirm('Do you wish to continue? (Y/N)'));

        if ($confirmation == 'y' OR $confirmation == 'yes' OR $confirmation == 1) {
            try {
                $user = User::create($input);
                $this->info($input['name'] . ' has been added to the system.');
            } catch (Illuminate\Database\QueryException $e) {
                $this->error($e->getMessage());
            } catch (Exception $e) {
                $this->error($e->getMessage());
            }
        } else {
            $this->error('Aborting');
        }
    }

    private function askPassword()
    {
        $password = $this->secret('Password: ');
        $passwordConfirmation = $this->secret('Re-enter password: ');

        if ($password !== $passwordConfirmation) {
            $this->error('Password doesn\'t match.');
            return $this->askPassword();
        }

        return $password;
    }
}
