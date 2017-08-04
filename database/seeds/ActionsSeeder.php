<?php

use Illuminate\Database\Seeder;

class ActionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Dashboard
        DB::table('actions')->insert([
            'controller_id' => 8,
            'name' => 'show',
            'is_active' => 1,
        ]);
        //Branches
        DB::table('actions')->insert([
            'controller_id' => 3,
            'name' => 'showList',
            'is_active' => 1,
        ]);
        DB::table('actions')->insert([
            'controller_id' => 3,
            'name' => 'create',
            'is_active' => 1,
        ]);
        //Departments
        DB::table('actions')->insert([
            'controller_id' => 4,
            'name' => 'showList',
            'is_active' => 1,
        ]);
        DB::table('actions')->insert([
            'controller_id' => 4,
            'name' => 'create',
            'is_active' => 1,
        ]);
        //External Organizations
        DB::table('actions')->insert([
            'controller_id' => 7,
            'name' => 'showList',
            'is_active' => 1,
        ]);
        DB::table('actions')->insert([
            'controller_id' => 7,
            'name' => 'create',
            'is_active' => 1,
        ]);
        //Positions
        DB::table('actions')->insert([
            'controller_id' => 5,
            'name' => 'showList',
            'is_active' => 1,
        ]);
        DB::table('actions')->insert([
            'controller_id' => 5,
            'name' => 'create',
            'is_active' => 1,
        ]);
        //Users(enterpiseController)
        DB::table('actions')->insert([
            'controller_id' => 14,
            'name' => 'showUsers',
            'is_active' => 1,
        ]);
        DB::table('actions')->insert([
            'controller_id' => 14,
            'name' => 'createUser',
            'is_active' => 1,
        ]);
        //security
        DB::table('actions')->insert([
            'controller_id' => 6,
            'name' => 'getSecurity',
            'is_active' => 1,
        ]);
        //setting
        DB::table('actions')->insert([
            'controller_id' => 6,
            'name' => 'setSecurity',
            'is_active' => 1,
        ]);

        //roles
        DB::table('actions')->insert([
            'controller_id' => 9,
            'name' => 'listUsersAndRoles',
            'is_active' => 1,
        ]);
        DB::table('actions')->insert([
            'controller_id' => 9,
            'name' => 'addNewRole',
            'is_active' => 1,
        ]);
        DB::table('actions')->insert([
            'controller_id' => 9,
            'name' => 'showRolesOfUser',
            'is_active' => 1,
        ]);
        DB::table('actions')->insert([
            'controller_id' => 9,
            'name' => 'addRoleToUser',
            'is_active' => 1,
        ]);
        DB::table('actions')->insert([
            'controller_id' => 9,
            'name' => 'deleteUsersRole',
            'is_active' => 1,
        ]);
        DB::table('actions')->insert([
            'controller_id' => 9,
            'name' => 'showRoles',
            'is_active' => 1,
        ]);
        DB::table('actions')->insert([
            'controller_id' => 9,
            'name' => 'deactivate',
            'is_active' => 1,
        ]);
        DB::table('actions')->insert([
            'controller_id' => 9,
            'name' => 'activate',
            'is_active' => 1,
        ]);
        DB::table('actions')->insert([
            'controller_id' => 1,
            'name' => 'createUserByAdmin',
            'is_active' => 1,
        ]);
        //Activate user
        DB::table('actions')->insert([
            'controller_id' => 10,
            'name' => 'activate',
            'is_active' => 1,
        ]);
        //Deactivate user
        DB::table('actions')->insert([
            'controller_id' => 10,
            'name' => 'deactivate',
            'is_active' => 1,
        ]);
        //edit role
        DB::table('actions')->insert([
            'controller_id' => 9,
            'name' => 'edit',
            'is_active' => 1,
        ]);

    }
}
