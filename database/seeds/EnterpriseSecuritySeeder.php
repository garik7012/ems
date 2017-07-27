<?php

use Illuminate\Database\Seeder;

class EnterpriseSecuritySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $enterpriseSettings = [
            'auth_type_id' => 0,
            'password_policy_id' => 0,
            'is_sms_allow' => 0,
            'max_login_attempts' => 10,
            'max_login_period' => 10,
            'max_hours_ban' => 10,
            'password_change_days' => 10,

        ];
        foreach ($enterpriseSettings as $key=>$value)
        DB::table('settings')->insert([
            'type' => 2,
            'key' => $key,
            'value' => $value,
            'item_id' => 4,
        ]);
    }
}
