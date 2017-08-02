<?php

use Illuminate\Database\Seeder;

class TestRoles extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users_and_roles')->insert([
            'enterprise_id' => 1,
            'user_id' => 2,
            'role_id' =>1
        ]);
        DB::table('roles')->insert([
        'enterprise_id' => 1,
        'name' => 'Show lists',
        'description' => "branches and departments lists"
        ]);
        DB::table('roles_and_actions')->insert([
            'enterprise_id' => 1,
            'role_id' => 1,
            'action_id' => 2
        ]);
        DB::table('roles_and_actions')->insert([
            'enterprise_id' => 1,
            'role_id' => 1,
            'action_id' => 4
        ]);
    }
}
