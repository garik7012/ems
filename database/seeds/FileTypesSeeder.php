<?php

use Illuminate\Database\Seeder;

class FileTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('file_types')->insert([
            'name' => 'logo'
        ]);

        DB::table('file_types')->insert([
            'name' => 'avatar'
        ]);
    }
}
