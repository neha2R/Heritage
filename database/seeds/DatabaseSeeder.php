<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     *
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //   $this->call(UserSeeder::class);
        //   $this->call(CountrySeeder::class);
        //  $this->call(StateSeeder::class);
        //  $this->call(CitySeeder::class);
        //  $this->call(FeedSeeder::class);
        //   $this->call(ThemeSeeder::class);
        //   $this->call(Frequency::class);
        //   $this->call(ProductCategorySeeder::class);
        //   $this->call(League::class);
         $this->call(NotificationSeeder::class);
         $this->call(NotificationDetailSeeder::class);

        $this->call(PrivacyDetailSeeder::class);
        $this->call(PrivacySeeder::class);


    }
}
