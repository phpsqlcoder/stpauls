<?php

use Illuminate\Database\Seeder;

class PaymentListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        $payments = [
            [
                'name' => 'Credit Card Payment',
                'is_active' => 1,
                'user_id' => 1,
            ],
            [
                'name' => 'Online Fund Transfer',
                'is_active' => 1,
                'user_id' => 1,
            ],
            [
                'name' => 'Money Transfer',
                'is_active' => 1,
                'user_id' => 1,
            ]
        ];

        DB::table('payment_list')->insert($payments);
    }
}
