<?php

use Illuminate\Database\Seeder;

class ProductPhotoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        $products_photos = [
            [
                'product_id' => '1',
                'name' => '',
                'description' => '',
                'path' => '1/190c0ed631ff9203aa55d20a4f62fde1.jpg',
                'status' => 'PUBLISHED',
                'is_primary' => '1',
                'created_by' => 1
            ],
            [
                'product_id' => '1',
                'name' => '',
                'description' => '',
                'path' => '1/french-roast.jpg',
                'status' => 'PUBLISHED',
                'is_primary' => '0',
                'created_by' => 1
            ],
            [
                'product_id' => '1',
                'name' => '',
                'description' => '',
                'path' => '1/french-toast.jpg',
                'status' => 'PUBLISHED',
                'is_primary' => '0',
                'created_by' => 1
            ],
            [
                'product_id' => '1',
                'name' => '',
                'description' => '',
                'path' => '1/hazel.jpg',
                'status' => 'PUBLISHED',
                'is_primary' => '0',
                'created_by' => 1
            ],

            [
                'product_id' => '2',
                'name' => '',
                'description' => '',
                'path' => '2/190c0ed631ff9203aa55d20a4f62fde2.jpg',
                'status' => 'PUBLISHED',
                'is_primary' => '1',
                'created_by' => 1
            ],
            [
                'product_id' => '2',
                'name' => '',
                'description' => '',
                'path' => '2/french-roast.jpg',
                'status' => 'PUBLISHED',
                'is_primary' => '0',
                'created_by' => 1
            ],
            [
                'product_id' => '2',
                'name' => '',
                'description' => '',
                'path' => '2/french-toast.jpg',
                'status' => 'PUBLISHED',
                'is_primary' => '0',
                'created_by' => 1
            ],
            [
                'product_id' => '2',
                'name' => '',
                'description' => '',
                'path' => '2/hazel.jpg',
                'status' => 'PUBLISHED',
                'is_primary' => '0',
                'created_by' => 1
            ],

            [
                'product_id' => '3',
                'name' => '',
                'description' => '',
                'path' => '3/190c0ed631ff9203aa55d20a4f62fde3.jpg',
                'status' => 'PUBLISHED',
                'is_primary' => '1',
                'created_by' => 1
            ],
            [
                'product_id' => '3',
                'name' => '',
                'description' => '',
                'path' => '3/french-roast.jpg',
                'status' => 'PUBLISHED',
                'is_primary' => '0',
                'created_by' => 1
            ],
            [
                'product_id' => '3',
                'name' => '',
                'description' => '',
                'path' => '3/french-toast.jpg',
                'status' => 'PUBLISHED',
                'is_primary' => '0',
                'created_by' => 1
            ],
            [
                'product_id' => '3',
                'name' => '',
                'description' => '',
                'path' => '3/hazel.jpg',
                'status' => 'PUBLISHED',
                'is_primary' => '0',
                'created_by' => 1
            ],
            
            [
                'product_id' => '4',
                'name' => '',
                'description' => '',
                'path' => '4/190c0ed631ff9203aa55d20a4f62fde4.jpg',
                'status' => 'PUBLISHED',
                'is_primary' => '1',
                'created_by' => 1
            ],
            [
                'product_id' => '4',
                'name' => '',
                'description' => '',
                'path' => '4/french-roast.jpg',
                'status' => 'PUBLISHED',
                'is_primary' => '0',
                'created_by' => 1
            ],
            [
                'product_id' => '4',
                'name' => '',
                'description' => '',
                'path' => '4/french-toast.jpg',
                'status' => 'PUBLISHED',
                'is_primary' => '0',
                'created_by' => 1
            ],
            [
                'product_id' => '4',
                'name' => '',
                'description' => '',
                'path' => '4/hazel.jpg',
                'status' => 'PUBLISHED',
                'is_primary' => '0',
                'created_by' => 1
            ],

            [
                'product_id' => '5',
                'name' => '',
                'description' => '',
                'path' => '5/190c0ed631ff9203aa55d20a4f62fde5.jpg',
                'status' => 'PUBLISHED',
                'is_primary' => '1',
                'created_by' => 1
            ],
            [
                'product_id' => '5',
                'name' => '',
                'description' => '',
                'path' => '5/french-roast.jpg',
                'status' => 'PUBLISHED',
                'is_primary' => '0',
                'created_by' => 1
            ],
            [
                'product_id' => '5',
                'name' => '',
                'description' => '',
                'path' => '5/french-toast.jpg',
                'status' => 'PUBLISHED',
                'is_primary' => '0',
                'created_by' => 1
            ],
            [
                'product_id' => '5',
                'name' => '',
                'description' => '',
                'path' => '5/hazel.jpg',
                'status' => 'PUBLISHED',
                'is_primary' => '0',
                'created_by' => 1
            ],

            [
                'product_id' => '6',
                'name' => '',
                'description' => '',
                'path' => '6/190c0ed631ff9203aa55d20a4f62fde6.jpg',
                'status' => 'PUBLISHED',
                'is_primary' => '1',
                'created_by' => 1
            ],
            [
                'product_id' => '6',
                'name' => '',
                'description' => '',
                'path' => '6/french-roast.jpg',
                'status' => 'PUBLISHED',
                'is_primary' => '0',
                'created_by' => 1
            ],
            [
                'product_id' => '6',
                'name' => '',
                'description' => '',
                'path' => '6/french-toast.jpg',
                'status' => 'PUBLISHED',
                'is_primary' => '0',
                'created_by' => 1
            ],
            [
                'product_id' => '6',
                'name' => '',
                'description' => '',
                'path' => '6/hazel.jpg',
                'status' => 'PUBLISHED',
                'is_primary' => '0',
                'created_by' => 1
            ],

            [
                'product_id' => '7',
                'name' => '',
                'description' => '',
                'path' => '7/190c0ed631ff9203aa55d20a4f62fde7.jpg',
                'status' => 'PUBLISHED',
                'is_primary' => '1',
                'created_by' => 1
            ],
            [
                'product_id' => '7',
                'name' => '',
                'description' => '',
                'path' => '7/french-roast.jpg',
                'status' => 'PUBLISHED',
                'is_primary' => '0',
                'created_by' => 1
            ],
            [
                'product_id' => '7',
                'name' => '',
                'description' => '',
                'path' => '7/french-toast.jpg',
                'status' => 'PUBLISHED',
                'is_primary' => '0',
                'created_by' => 1
            ],
            [
                'product_id' => '7',
                'name' => '',
                'description' => '',
                'path' => '7/hazel.jpg',
                'status' => 'PUBLISHED',
                'is_primary' => '0',
                'created_by' => 1
            ],

            [
                'product_id' => '8',
                'name' => '',
                'description' => '',
                'path' => '8/190c0ed631ff9203aa55d20a4f62fde8.jpg',
                'status' => 'PUBLISHED',
                'is_primary' => '1',
                'created_by' => 1
            ],
            [
                'product_id' => '8',
                'name' => '',
                'description' => '',
                'path' => '8/french-roast.jpg',
                'status' => 'PUBLISHED',
                'is_primary' => '0',
                'created_by' => 1
            ],
            [
                'product_id' => '8',
                'name' => '',
                'description' => '',
                'path' => '8/french-toast.jpg',
                'status' => 'PUBLISHED',
                'is_primary' => '0',
                'created_by' => 1
            ],
            [
                'product_id' => '8',
                'name' => '',
                'description' => '',
                'path' => '8/hazel.jpg',
                'status' => 'PUBLISHED',
                'is_primary' => '0',
                'created_by' => 1
            ]
            

        ];

        DB::table('product_photos')->insert($products_photos);
    }
}
