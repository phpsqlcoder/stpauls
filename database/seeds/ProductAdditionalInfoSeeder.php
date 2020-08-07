<?php

use Illuminate\Database\Seeder;

class ProductAdditionalInfoSeeder extends Seeder
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
                DB::table('product_additional_info')
                    ->insert([
                        'product_id' => $i,
                        'synopsis' => '',
                        'authors' => 'Fr. Jeffrey S. Segovia',
                        'materials' => 'Paperback (KIT-SP02720)',
                        'no_of_pages' => 214,
                        'isbn' => '978-971-004-512-9',
                        'editorial_reviews' => '',
                        'about_author' => '<p><img src="https://stpauls.ph/file-manager/images/Books/SP/SP%20AUT/HeartLines%20AUT.jpg" /></p>',
                        'additional_info' => ''
                    ]);
            }
            
        }
    }
}
