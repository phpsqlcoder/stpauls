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
        $faker = \Faker\Factory::create();

        $categories = [
            [
                'parent_id' => '0',
                'name' => 'Books',
                'slug' => 'books',
                'description' => '',
                'status' => 'PUBLISHED',
                'created_by' => 1
            ],
            [
                'parent_id' => '0',
                'name' => 'Bible',
                'slug' => 'bible',
                'description' => '',
                'status' => 'PUBLISHED',
                'created_by' => 1
            ],
            [
                'parent_id' => '0',
                'name' => 'Straight from the Heart',
                'slug' => 'straight-from-the-heart',
                'description' => '',
                'status' => 'PUBLISHED',
                'created_by' => 1
            ],
            [
                'parent_id' => '0',
                'name' => 'Devotionals',
                'slug' => 'devotionals',
                'description' => '',
                'status' => 'PUBLISHED',
                'created_by' => 1
            ],
            [
                'parent_id' => '0',
                'name' => 'Others',
                'slug' => 'others',
                'description' => '',
                'status' => 'PUBLISHED',
                'created_by' => 1
            ]
        ];

        DB::table('product_categories')->insert($categories);
    }
}
