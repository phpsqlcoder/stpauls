<?php

use Illuminate\Database\Seeder;

class PaymentOptionSeeder extends Seeder
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
                'payment_id' => '2',
                'name' => 'Metrobank',
                'type' => 'bank',
                'account_no' => '0123456789',
                'branch' => 'Pasig',
                'qrcode' => '',
                'is_active' => 1,
                'is_default' => 1,
                'user_id' => 1
            ],
            [
                'payment_id' => '2',
                'name' => 'BPI',
                'type' => 'bank',
                'account_no' => '0123456789',
                'branch' => 'Pasig',
                'qrcode' => '',
                'is_active' => 1,
                'is_default' => 1,
                'user_id' => 1
            ],
            [
                'payment_id' => '2',
                'name' => 'BDO',
                'type' => 'bank',
                'account_no' => '0123456789',
                'branch' => 'Pasig',
                'qrcode' => '',
                'is_active' => 1,
                'is_default' => 1,
                'user_id' => 1
            ],
            [
                'payment_id' => '3',
                'name' => 'GCash',
                'type' => 'remittance',
                'account_no' => '',
                'branch' => '',
                'qrcode' => '0123456',
                'is_active' => 1,
                'is_default' => 0,
                'user_id' => 1
            ]
        ];

        DB::table('payment_options')->insert($options);
    }
}
