<?php

use Illuminate\Database\Seeder;

class ControllersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('controllers')->insert([
            'name' => 'registration',
            'module_id' => 1,
            'is_active' => 1,
        ]);
        DB::table('controllers')->insert([
            'name' => 'authorization',
            'module_id' => 1,
            'is_active' => 1,
        ]);
        DB::table('controllers')->insert([
            'name' => 'branches',
            'module_id' => 2,
            'is_active' => 1,
        ]);
        DB::table('controllers')->insert([
            'name' => 'departments',
            'module_id' => 2,
            'is_active' => 1,
        ]);
        DB::table('controllers')->insert([
            'name' => 'positions',
            'module_id' => 2,
            'is_active' => 1,
        ]);
        DB::table('controllers')->insert([
            'name' => 'settings',
            'module_id' => 2,
            'is_active' => 1,
        ]);
        DB::table('controllers')->insert([
            'name' => 'externalorganisations',
            'module_id' => 2,
            'is_active' => 1,
        ]);
        DB::table('controllers')->insert([
            'name' => 'dashboard',
            'module_id' => 3,
            'is_active' => 1,
        ]);
        DB::table('controllers')->insert([
            'name' => 'roles',
            'module_id' => 3,
            'is_active' => 1,
        ]);
        DB::table('controllers')->insert([
            'name' => 'settings',
            'module_id' => 3,
            'is_active' => 1,
        ]);
        DB::table('controllers')->insert([
            'name' => 'files',
            'module_id' => 4,
            'is_active' => 1,
        ]);
        DB::table('controllers')->insert([
            'name' => 'users',
            'module_id' => 5,
            'is_active' => 1,
        ]);
        DB::table('controllers')->insert([
            'name' => 'emails',
            'module_id' => 5,
            'is_active' => 1,
        ]);
        DB::table('controllers')->insert([
            'name' => 'enterprise',
            'module_id' => 2,
            'is_active' => 1,
        ]);
    }
}
