<?php

use Illuminate\Database\Seeder;

class CheckoutOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        $options = [
            [
                'name' => 'Cash on Delivery',
                'delivery_rate' => 100,
                'service_fee' => 0,
                'minimum_purchase' => 1000,
                'allowed_days' => 'Mon|Tue|Wed|Thu|Fri',
                'allowed_time_from' => '8:00',
                'allowed_time_to' => '12:00',
                'reminder' => 'lorem ipsum',
                'is_active' => 1,
                'user_id' => 1
            ],
            [
                'name' => 'Store Pick Up',
                'delivery_rate' => 0,
                'service_fee' => 0,
                'minimum_purchase' => 0,
                'allowed_days' => 'Mon|Tue|Wed|Thu|Fri',
                'allowed_time_from' => '8:00',
                'allowed_time_to' => '12:00',
                'reminder' => 'lorem ipsum',
                'is_active' => 1,
                'user_id' => 1
            ],
            [
                'name' => 'Door 2 Door Delivery',
                'delivery_rate' => 0,
                'service_fee' => 0,
                'minimum_purchase' => 0,
                'allowed_days' => '',
                'allowed_time_from' => '',
                'allowed_time_to' => '',
                'reminder' => '',
                'is_active' => 1,
                'user_id' => 1
            ],
            [
                'name' => 'Same Day Delivery',
                'delivery_rate' => 0,
                'service_fee' => 100,
                'minimum_purchase' => 0,
                'allowed_days' => 'Mon|Tue|Wed|Thu|Fri',
                'allowed_time_from' => '8:00',
                'allowed_time_to' => '12:00',
                'reminder' => 'lorem ipsum',
                'is_active' => 1,
                'user_id' => 1
            ]
        ];

        DB::table('ecommerce_checkout_options')->insert($options);
    }
}
