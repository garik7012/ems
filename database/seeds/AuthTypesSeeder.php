<?php

use Illuminate\Database\Seeder;

class AuthTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('auth_types')->insert([
            'name' => 'Single-factor (uname/pass)',
            'type' => 1,
        ]);

        DB::table('auth_types')->insert([
            'name' => 'Two-factor',
            'type' => 2,
        ]);

        DB::table('auth_types')->insert([
            'name' => '2 factor with "trusted devices"',
            'type' => 3,
        ]);

        DB::table('auth_types')->insert([
            'name' => 'Two-factor (picture based)',
            'type' => 4,
        ]);

        DB::table('auth_types')->insert([
            'name' => '2 factor (picture based) with "trusted devices"',
            'type' => 5,
        ]);
    }
}
