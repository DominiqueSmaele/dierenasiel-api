<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Laravel\Passport\Passport;

class OAuthKeySeeder extends Seeder
{
    public function run() : void
    {
        if (! empty(config('passport.private_key')) || ! empty(config('passport.public_key'))) {
            return;
        }

        if (file_exists(Passport::keyPath('oauth-private.key')) && file_exists(Passport::keyPath('oauth-public.key'))) {
            return;
        }

        $this->command->call('passport:keys');
    }
}
