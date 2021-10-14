<?php

use Illuminate\Database\Seeder;

class FeedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = array(
            array( 'title' => 'Single Posts',
            'description' => 'This is description',),
            array('title' => 'Modules',
            'description' => 'This is description'),
            array('title' => 'Collections',
            'description' => 'This is description'),
        );
        DB::table('feeds')->insert($data);

    }
}
