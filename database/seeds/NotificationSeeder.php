<?php

use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = array(
            array('title' => 'Quizzes'),
            array('title' => 'Content'),
            array('title' => 'Shop',),
        );
        DB::table('notifications')->insert($data);
    }
}
