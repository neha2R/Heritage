<?php

use Illuminate\Database\Seeder;

class ThemeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    
        $data = array(
            array('title' => 'Intangible Heritage',
            'description' => 'This is description',),
            array('title' => 'Natural Heritage',
            'description' => 'This is description',),
            array('title' => 'Tangible Heritage',
            'description' => 'This is description',),
        );
        DB::table('themes')->insert($data);

    }
}
