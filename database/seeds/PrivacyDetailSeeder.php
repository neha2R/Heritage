<?php

use Illuminate\Database\Seeder;

class PrivacyDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = array(
            array('title' => 'anyone','privacy_id'=>1),
            array('title' => 'only user i have added','privacy_id'=>1),
            array('title' => 'only me','privacy_id'=>1),
            array('title' => 'every one','privacy_id'=>2),
            array('title' => 'no one','privacy_id'=>2),
            array('title' => 'every one','privacy_id'=>3),
            array('title' => 'no one','privacy_id'=>3), 
        );
        DB::table('privacy_details')->insert($data);
    }
}
