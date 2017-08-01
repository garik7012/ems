<?php

use Illuminate\Database\Seeder;

class ModulesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('modules')->insert([
            'name' => 'security',
            'is_active' => 1,
        ]);
        DB::table('modules')->insert([
            'name' => 'enterprises',
            'is_active' => 1,
        ]);
        DB::table('modules')->insert([
            'name' => 'users',
            'is_active' => 1,
        ]);
        DB::table('modules')->insert([
            'name' => 'tools',
            'is_active' => 1,
        ]);
        DB::table('modules')->insert([
            'name' => 'logs',
            'is_active' => 1,
        ]);
    }
}
