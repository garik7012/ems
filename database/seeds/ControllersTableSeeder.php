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
            'name' => 'Registration',
            'module_id' => 1,
            'is_active' => 1,
        ]);
        DB::table('controllers')->insert([
            'name' => 'Authorization',
            'module_id' => 1,
            'is_active' => 1,
        ]);
        DB::table('controllers')->insert([
            'name' => 'Branches',
            'module_id' => 2,
            'is_active' => 1,
            'table' => 'branches'
        ]);
        DB::table('controllers')->insert([
            'name' => 'Departments',
            'module_id' => 2,
            'is_active' => 1,
            'table' => 'departments'
        ]);
        DB::table('controllers')->insert([
            'name' => 'Positions',
            'module_id' => 2,
            'is_active' => 1,
        ]);
        DB::table('controllers')->insert([
            'name' => 'Settings',
            'module_id' => 2,
            'is_active' => 1,
        ]);
        DB::table('controllers')->insert([
            'name' => 'Externalorganisations',
            'module_id' => 2,
            'is_active' => 1,
        ]);
        DB::table('controllers')->insert([
            'name' => 'Dashboard',
            'module_id' => 3,
            'is_active' => 1,
        ]);
        DB::table('controllers')->insert([
            'name' => 'Roles',
            'module_id' => 3,
            'is_active' => 1,
        ]);
        DB::table('controllers')->insert([
            'name' => 'Settings',
            'module_id' => 3,
            'is_active' => 1,
        ]);
        DB::table('controllers')->insert([
            'name' => 'Files',
            'module_id' => 4,
            'is_active' => 1,
        ]);
        DB::table('controllers')->insert([
            'name' => 'Users',
            'module_id' => 5,
            'is_active' => 1,
        ]);
        DB::table('controllers')->insert([
            'name' => 'Emails',
            'module_id' => 5,
            'is_active' => 1,
        ]);
        DB::table('controllers')->insert([
            'name' => 'Enterprise',
            'module_id' => 2,
            'is_active' => 1,
        ]);
        DB::table('controllers')->insert([
            'name' => 'Users',
            'module_id' => 2,
            'is_active' => 1,
        ]);
        DB::table('controllers')->insert([
            'name' => 'Supervisors',
            'module_id' => 2,
            'is_active' => 1,
        ]);
        //Logs
        DB::table('controllers')->insert([
            'name' => 'Logins',
            'module_id' => 5,
            'is_active' => 1,
        ]);
    }
}
