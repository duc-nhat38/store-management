<?php

namespace Database\Seeders;

use App\Enums\StoreStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $managerIds = \App\Models\User::pluck('id')->toArray();

        for ($i = 1; $i <= 100; $i++) {
            \App\Models\Store::create([
                'name' => "Store {$i}",
                'manager_id' => $managerIds[array_rand($managerIds)],
                'email' => "store{$i}@gmail.com",
                'phone_number' => "0123456789",
                'address' => 'Ho Chi Minh City, Viet Nam',
                'fax' => null,
                'operation_start_date' => '2023-05-01',
                'number_of_employees' => rand(1, 100),
                'status' => rand(StoreStatus::CLOSED, StoreStatus::ACTIVE),
                'note' => null
            ]);
        }
    }
}
