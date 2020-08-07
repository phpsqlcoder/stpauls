<?php

use Illuminate\Database\Seeder;

class ProductTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        for($i=1; $i<=8; $i++){
            for($x=1;$x<=8;$x++){
                DB::table('product_tags')
                    ->insert([
                        'product_id' => $i,
                        'tag' => $faker->word,
                        'created_by' => 1
                    ]);
            }
            
        }
    }
}
