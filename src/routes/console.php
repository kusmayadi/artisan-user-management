<?php

use Kusmayadi\ArtisanUser\Console\Commands\UserAdd;
use Orchestra\Testbench\Console\Kernel;


Artisan::console('user:add', UserAdd::class);
