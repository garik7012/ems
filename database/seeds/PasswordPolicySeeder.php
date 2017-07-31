<?php

use Illuminate\Database\Seeder;

class PasswordPolicySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('password_policies')->insert([
            'name' => 'upper',
            'description' => 'At least two uppercase',
            'pattern' => "(?=(?:[^A-Z]*[A-Z]){2})"
        ]);
        DB::table('password_policies')->insert([
            'name' => 'special',
            'description' => "At least one \"special\"",
            'pattern' => "(?=[^!@#$&*]*[!@#$&*])"
        ]);
        DB::table('password_policies')->insert([
            'name' => 'digit',
            'description' => "At least two digit",
            'pattern' => "(?=[^!@#$&*]*[!@#$&*])"
        ]);
        DB::table('password_policies')->insert([
            'name' => 'min',
            'description' => "Password length is",
            'pattern' => "8"
        ]);
    }
}
