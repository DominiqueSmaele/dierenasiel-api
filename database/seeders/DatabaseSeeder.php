<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class DatabaseSeeder extends Seeder
{
    public function run() : void
    {
        $this->call(OAuthKeySeeder::class);

        if (App::environment('production')) {
            return;
        }

        if (! $this->command->confirm('Are you sure you want to run the seeders that may alter your data?')) {
            return;
        }

        $this->call(LaratrustSeeder::class);
    }
}
