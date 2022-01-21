<?php

use Illuminate\Database\Seeder;

class PrivacySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = array(
            array('title' => 'My Profile is visible to'),
            array('title' => 'I can be added by'),
            array('title' => 'I can be invited by',),
        );
        DB::table('privacies')->insert($data);
    }
}
