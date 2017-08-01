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
            'action_id' => 2,
            'position' => 1,
            'is_active' => 1,
            'description' => '/'
        ]);
        DB::table('menu')->insert([
            'name' => 'Branches',
            'position' => 2,
            'is_active' => 1,
        ]);
        DB::table('menu')->insert([
            'name' => 'List',
            'parent_id' => 2,
            'action_id' => 4,
            'is_active' => 1,
            'description' => '/branches/list'
        ]);
        DB::table('menu')->insert([
            'name' => 'Add',
            'parent_id' => 2,
            'action_id' => 5,
            'is_active' => 1,
            'description' => '/branches/create'
        ]);
        DB::table('menu')->insert([
            'name' => 'Departments',
            'position' => 3,
            'is_active' => 1,
        ]);
        DB::table('menu')->insert([
            'name' => 'List',
            'parent_id' => 5,
            'action_id' => 7,
            'is_active' => 1,
            'description' => '/departments/list'
        ]);
        DB::table('menu')->insert([
            'name' => 'Add',
            'parent_id' => 5,
            'action_id' => 8,
            'is_active' => 1,
            'description' => '/departments/create'
        ]);
        DB::table('menu')->insert([
            'name' => 'External Organisations',
            'position' => 4,
            'is_active' => 0,
        ]);
        DB::table('menu')->insert([
            'name' => 'List',
            'parent_id' => 8,
            'action_id' => 10,
            'is_active' => 1,
        ]);
        DB::table('menu')->insert([
            'name' => 'Add',
            'parent_id' => 8,
            'action_id' => 11,
            'is_active' => 1,
        ]);
        DB::table('menu')->insert([
            'name' => 'Positions',
            'position' => 5,
            'is_active' => 1,
        ]);
        DB::table('menu')->insert([
            'name' => 'List',
            'parent_id' => 11,
            'action_id' => 13,
            'is_active' => 1,
            'description' => '/positions/list'
        ]);
        DB::table('menu')->insert([
            'name' => 'Add',
            'parent_id' => 11,
            'action_id' => 14,
            'is_active' => 1,
            'description' => '/positions/create'
        ]);
        DB::table('menu')->insert([
            'name' => 'Users',
            'position' => 6,
            'is_active' => 1,
        ]);
        DB::table('menu')->insert([
            'name' => 'List',
            'parent_id' => 14,
            'action_id' => 16,
            'is_active' => 1,
            'description' => '/user/list'
        ]);
        DB::table('menu')->insert([
            'name' => 'Add',
            'parent_id' => 14,
            'action_id' => 17,
            'is_active' => 1,
            'description' => '/user/create'
        ]);
        DB::table('menu')->insert([
            'name' => 'Security',
            'action_id' => 18,
            'position' => 7,
            'is_active' => 1,
            'description' => '/security'
        ]);
        DB::table('menu')->insert([
            'name' => 'Setting',
            'action_id' => 19,
            'position' => 8,
        ]);

    }
}
