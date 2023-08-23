<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class PlaygroundCommand extends Command
{
    protected $signature = 'play';

    protected $description = 'Command description';

    public function handle(): void
    {
        User::firstOrCreate();
        User::createOrFirst();
    }
}
