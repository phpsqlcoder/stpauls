<?php

use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
    * Run the database seeds.
    *
    * @return void
    */
    public function run()
    {
        $roles = [
            [
                'name' => 'Admin',
                'description' => 'Administrator of the system',
                'created_by' => 1
            ],
            [
                'name' => 'User',
                'description' => 'user of the system',
                'created_by' => 1
            ],
            [
                'name' => 'Customer',
                'description' => 'customer',
                'created_by' => 1
            ]
        ];

        DB::table('role')->insert($roles);
    }
}
