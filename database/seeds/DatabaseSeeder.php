<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      // $this->call(UsersTableSeeder::class);
      // $this->call(EnterpriseSecuritySeeder::class);
        $this->call(AuthTypesSeeder::class);
        $this->call(PasswordPolicySeeder::class);
        $this->call(MenuSeeder::class);
        $this->call(ControllersTableSeeder::class);
        $this->call(ModulesTableSeeder::class);
        $this->call(ActionsSeeder::class);
        $this->call(TestRoles::class);
    }
}
