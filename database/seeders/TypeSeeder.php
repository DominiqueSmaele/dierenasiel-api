<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypeSeeder extends Seeder
{
    public function run() : void
    {
        $types = [
            ['name' => 'Honden'],
            ['name' => 'Katten'],
            ['name' => 'Konijnen en knaagdieren'],
            ['name' => 'Weidedieren'],
            ['name' => 'Overige dieren'],
        ];

        DB::table('types')->insert($types);
    }
}
