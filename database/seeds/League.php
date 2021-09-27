<?php

use Illuminate\Database\Seeder;

class League extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = array(
            array('title' => 'Expert',
            'description' => 'Expert league','xp' =>2000,),
            array('title' => 'Scholar',
            'description' => 'Scholar league','xp' =>1600),
            array('title' => 'Culture Vulutre',
            'description' => 'Scholar league','xp' =>1600),
            array('title' => 'Dabbler',
            'description' => 'Dabbler league','xp' =>800),
            array('title' => 'Initiate',
            'description' => 'Initiate league','xp' =>400),
          
  );
        DB::table('leagues')->insert($data);
    }
}
