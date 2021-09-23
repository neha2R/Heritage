<?php

use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = array(
            array('name' => 'Gift',
            'status' => '1'),
            array('name' => 'Product',
            'status' => '1')
        );
        DB::table('product_categories')->insert($data);
    }
}
