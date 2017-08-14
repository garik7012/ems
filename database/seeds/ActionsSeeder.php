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
        //Enterprise/Users
        DB::table('actions')->insert([
            'controller_id' => 15,
            'name' => 'showList',
            'is_active' => 1,
        ]);
        DB::table('actions')->insert([
            'controller_id' => 15,
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
            'name' => 'showList',
            'is_active' => 1,
        ]);
        DB::table('actions')->insert([
            'controller_id' => 19,
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
            'controller_id' => 19,
            'name' => 'showRoles',
            'is_active' => 1,
        ]);
        DB::table('actions')->insert([
            'controller_id' => 19,
            'name' => 'deactivate',
            'is_active' => 1,
        ]);
        DB::table('actions')->insert([
            'controller_id' => 19,
            'name' => 'activate',
            'is_active' => 1,
        ]);
        DB::table('actions')->insert([
            'controller_id' => 1,
            'name' => 'createUserByAdmin',
            'is_active' => 1,
        ]);
        //Activate user (/Enterprises/Users)
        DB::table('actions')->insert([
            'controller_id' => 15,
            'name' => 'activate',
            'is_active' => 1,
        ]);
        //Deactivate user (/Enterprises/Users)
        DB::table('actions')->insert([
            'controller_id' => 15,
            'name' => 'deactivate',
            'is_active' => 1,
        ]);
        //edit role
        DB::table('actions')->insert([
            'controller_id' => 19,
            'name' => 'edit',
            'is_active' => 1,
        ]);

        DB::table('actions')->insert([
            'controller_id' => 15,
            'name' => 'showUserSettings',
            'is_active' => 1,
        ]);

        DB::table('actions')->insert([
            'controller_id' => 15,
            'name' => 'changeUsersSettings',
            'is_active' => 1,
        ]);
        //login as user
        DB::table('actions')->insert([
            'controller_id' => 15,
            'name' => 'loginAsUser',
            'is_active' => 1,
        ]);
        //show user profile
        DB::table('actions')->insert([
            'controller_id' => 10,
            'name' => 'userProfile',
            'is_active' => 1,
        ]);
        //change user profile
        DB::table('actions')->insert([
            'controller_id' => 10,
            'name' => 'editUserProfile',
            'is_active' => 1,
        ]);
        //show supervisor list
        DB::table('actions')->insert([
            'controller_id' => 16,
            'name' => 'showList',
            'is_active' => 1,
        ]);
        DB::table('actions')->insert([
            'controller_id' => 16,
            'name' => 'edit',
            'is_active' => 1,
        ]);
        DB::table('actions')->insert([
            'controller_id' => 16,
            'name' => 'delete',
            'is_active' => 1,
        ]);
        DB::table('actions')->insert([
            'controller_id' => 16,
            'name' => 'add',
            'is_active' => 1,
        ]);
        //logs/login
        DB::table('actions')->insert([
            'controller_id' => 17,
            'name' => 'show',
            'is_active' => 1,
        ]);
        //enterprises/branch
        DB::table('actions')->insert([
            'controller_id' => 3,
            'name' => 'edit',
            'is_active' => 1,
        ]);
        DB::table('actions')->insert([
            'controller_id' => 3,
            'name' => 'activate',
            'is_active' => 1,
        ]);
        DB::table('actions')->insert([
            'controller_id' => 3,
            'name' => 'deactivate',
            'is_active' => 1,
        ]);
        //enterprises/department
        DB::table('actions')->insert([
            'controller_id' => 4,
            'name' => 'edit',
            'is_active' => 1,
        ]);
        DB::table('actions')->insert([
            'controller_id' => 4,
            'name' => 'activate',
            'is_active' => 1,
        ]);
        DB::table('actions')->insert([
            'controller_id' => 4,
            'name' => 'deactivate',
            'is_active' => 1,
        ]);
        //Users/UsersAndControllers
        DB::table('actions')->insert([
            'controller_id' => 18,
            'name' => 'showList',
            'is_active' => 1,
        ]);
        DB::table('actions')->insert([
            'controller_id' => 18,
            'name' => 'create',
            'is_active' => 1,
        ]);
        DB::table('actions')->insert([
            'controller_id' => 18,
            'name' => 'delete',
            'is_active' => 1,
        ]);
        DB::table('actions')->insert([
            'controller_id' => 18,
            'name' => 'edit',
            'is_active' => 1,
        ]);
        //logs/actions
        DB::table('actions')->insert([
            'controller_id' => 20,
            'name' => 'show',
            'is_active' => 1,
        ]);
        //enterprises/users
        DB::table('actions')->insert([
            'controller_id' => 15,
            'name' => 'showUserProfile',
            'is_active' => 1,
        ]);
        DB::table('actions')->insert([
            'controller_id' => 15,
            'name' => 'changeUserProfile',
            'is_active' => 1,
        ]);
        //Enterprises/Positions
        DB::table('actions')->insert([
            'controller_id' => 5,
            'name' => 'activate',
            'is_active' => 1,
        ]);
        DB::table('actions')->insert([
            'controller_id' => 5,
            'name' => 'deactivate',
            'is_active' => 1,
        ]);
        DB::table('actions')->insert([
            'controller_id' => 5,
            'name' => 'edit',
            'is_active' => 1,
        ]);
        //Users/Departments
        DB::table('actions')->insert([
            'controller_id' => 21,
            'name' => 'showList',
            'is_active' => 1,
        ]);
        //Users/Positions
        DB::table('actions')->insert([
            'controller_id' => 22,
            'name' => 'showList',
            'is_active' => 1,
        ]);
        //Users/Branches
        DB::table('actions')->insert([
            'controller_id' => 23,
            'name' => 'showList',
            'is_active' => 1,
        ]);
        //Users/Branches
        DB::table('actions')->insert([
            'controller_id' => 23,
            'name' => 'editUsersBranch',
            'is_active' => 1,
        ]);
        //Users/Department
        DB::table('actions')->insert([
            'controller_id' => 21,
            'name' => 'editUsersDepartment',
            'is_active' => 1,
        ]);
    }
}
