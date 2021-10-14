<?php

use Illuminate\Database\Seeder;

class Frequency extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = array(
            array('title' => 'Daily',
            'description' => 'Daily quiz play one time a day by user',),
            array('title' => 'Weekly',
            'description' => 'Weekely quiz play one time in a week',),
            array('title' => 'Monthly',
            'description' => 'Monthly quiz user play one time in a month',),
        );
        DB::table('frequencies')->insert($data);
    }
}
