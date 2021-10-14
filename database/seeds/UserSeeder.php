<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Heritage',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin123'),
            'dob' => date('Y-m-d'),
            'type' => '0',
        ],
        [
            'name' => 'Heritage User',
            'email' => 'user@gmail.com',
            'password' => Hash::make('user123'),
            'dob' => '1992-12-06',
            'type' => '2',
        ]);
    }
}
