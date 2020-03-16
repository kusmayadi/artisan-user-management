<?php

namespace Kusmayadi\ArtisanUser\Console\Commands;

use Illuminate\Console\Command;
use Kusmayadi\ArtisanUser\Models\User;

class UserList extends Command
{
    protected $signature = "user:list";
    protected $description = "List users";

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $users = User::all();

        $this->info('Users');
        $this->info('========================================');
        $this->info('|  id  |              Name              |');
        $this->info('========================================');

        foreach ($users as $user)
        {
            $this->info('|' . str_pad($user->id, 6, ' ', STR_PAD_BOTH) . '|' . str_pad($user->name, 32, ' ', STR_PAD_BOTH) . '|');
        }

        $this->info('========================================');

    }
}
