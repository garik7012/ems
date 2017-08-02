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
            'name' => 'Security',
            'is_active' => 1,
        ]);
        DB::table('modules')->insert([
            'name' => 'Enterprises',
            'is_active' => 1,
        ]);
        DB::table('modules')->insert([
            'name' => 'Users',
            'is_active' => 1,
        ]);
        DB::table('modules')->insert([
            'name' => 'Tools',
            'is_active' => 1,
        ]);
        DB::table('modules')->insert([
            'name' => 'Logs',
            'is_active' => 1,
        ]);
    }
}
