<?php

use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('menu')->insert([
            'name' => 'Dashboard',
            'is_for_all_users' => 1,
            'action_id' => 1,
            'position' => 1,
            'is_active' => 1,
        ]);
        DB::table('menu')->insert([
            'name' => 'Branches',
            'position' => 2,
            'is_active' => 1,
        ]);
        DB::table('menu')->insert([
            'name' => 'List',
            'parent_id' => 2,
            'action_id' => 2,
            'position' => 3,
            'is_active' => 1,
        ]);
        DB::table('menu')->insert([
            'name' => 'Add',
            'parent_id' => 2,
            'action_id' => 3,
            'position' => 4,
            'is_active' => 1,
        ]);
        DB::table('menu')->insert([
            'name' => 'Departments',
            'position' => 5,
            'is_active' => 1,
        ]);
        DB::table('menu')->insert([
            'name' => 'List',
            'parent_id' => 5,
            'action_id' => 4,
            'position' => 6,
            'is_active' => 1,
        ]);
        DB::table('menu')->insert([
            'name' => 'Add',
            'parent_id' => 5,
            'action_id' => 5,
            'position' => 7,
            'is_active' => 1,
        ]);
        DB::table('menu')->insert([
            'name' => 'External Organisations',
            'position' => 8,
            'is_active' => 0,
        ]);
        DB::table('menu')->insert([
            'name' => 'List',
            'parent_id' => 8,
            'action_id' => 6,
            'position' => 9,
            'is_active' => 0,
        ]);
        DB::table('menu')->insert([
            'name' => 'Add',
            'parent_id' => 8,
            'action_id' => 7,
            'position' => 10,
            'is_active' => 0,
        ]);
        DB::table('menu')->insert([
            'name' => 'Positions',
            'position' => 11,
            'is_active' => 1,
        ]);
        DB::table('menu')->insert([
            'name' => 'List',
            'parent_id' => 11,
            'action_id' => 8,
            'position' => 12,
            'is_active' => 1,
        ]);
        DB::table('menu')->insert([
            'name' => 'Add',
            'parent_id' => 11,
            'action_id' => 9,
            'position' => 13,
            'is_active' => 1,
        ]);
        DB::table('menu')->insert([
            'name' => 'Users',
            'position' => 14,
            'is_active' => 1,
        ]);
        DB::table('menu')->insert([
            'name' => 'List',
            'parent_id' => 14,
            'action_id' => 10,
            'position' => 15,
            'is_active' => 1,
        ]);
        DB::table('menu')->insert([
            'name' => 'Add',
            'parent_id' => 14,
            'action_id' => 11,
            'position' => 16,
            'is_active' => 1,
        ]);
        DB::table('menu')->insert([
            'name' => 'Security',
            'action_id' => 12,
            'position' => 17,
            'is_active' => 1,
        ]);
        DB::table('menu')->insert([
            'name' => 'Setting',
            'action_id' => 13,
            'position' => 18,
            'is_active' => 0,
        ]);
        DB::table('menu')->insert([
            'name' => 'Roles',
            'position' => 19,
        ]);
        DB::table('menu')->insert([
            'name' => 'Users and roles',
            'action_id' => 14,
            'position' => 20,
            'parent_id' => 19,
        ]);
        DB::table('menu')->insert([
            'name' => 'Add new role',
            'action_id' => 15,
            'position' => 21,
            'parent_id' => 19,
        ]);
        DB::table('menu')->insert([
            'name' => 'Show roles',
            'action_id' => 19,
            'position' => 22,
            'parent_id' => 19,
        ]);
    }
}
