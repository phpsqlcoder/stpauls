<?php

use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        for($i=130; $i<=230; $i++){
            DB::table('products')
                ->insert([
                    'code' => 'PR000000'.$i,
                    'category_id' => 5,
                    'name' => 'Product '.$i,
                    'short_description' => '<p>This is short description</p>',
                    'description' => '<p>This is description</p>',
                    'price' => number_format($faker->numberBetween($min = 1, $max = 1000),4),
                    'currency' => 'PHP',
                    'size' => 'LWH 14 x 1 x 21',
                    'weight' => '500',
                    'status' => 'PUBLISHED',
                    'uom' => 'PC',
                    'slug' => 'product-'.$i,
                    'is_featured' => '1',
                    'created_by' => 1,
                    'meta_title' => '',
                    'meta_description' => '',
                    'meta_keyword' => '',
                ]);
        }

    }
}
