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
            'name' => 'simple',
            'description' => 'At least 8 symbols',
            'pattern' => "^(?=.*[a-z]).{8,}$"
        ]);
        DB::table('password_policies')->insert([
            'name' => 'stronger',
            'description' => "At least one \"special\", min 8 length",
            'pattern' => "^(?=.*[a-z])(?=.*[!@#$&*]).{8,}$"
        ]);
        DB::table('password_policies')->insert([
            'name' => 'strong',
            'description' => "Must have letter in upper and lower case, special character, min 12 length ",
            'pattern' => "^(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$&*]).{12,}$"
        ]);
    }
}
