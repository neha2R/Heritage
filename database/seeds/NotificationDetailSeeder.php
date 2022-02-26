<?php

use Illuminate\Database\Seeder;

class NotificationDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = array(
            array('title' => 'daily_classic_reminder','notification_id'=>1),
            array('title' => 'goal_reminder','notification_id'=>1),
            array('title' => 'new_tournaments','notification_id'=>1),
            array('title' => 'new_posts','notification_id'=>2),
            array('title' => 'new_products','notification_id'=>3),
            array('title' => 'new_experience','notification_id'=>3),
        );
        DB::table('notification_details')->insert($data);
    }
}
