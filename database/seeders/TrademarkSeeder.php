<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TrademarkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $trademarks = [
            [
                'name' => 'Samsung',
                'nation' => 'Korea'
            ],
            [
                'name' => 'Apple',
                'nation' => 'US'
            ],
            [
                'name' => 'Xiaomi',
                'nation' => 'China'
            ],
            [
                'name' => 'Oppo',
                'nation' => 'China'
            ],
        ];

        foreach ($trademarks as $trademark) {
            \App\Models\Trademark::create($trademark);
        }
    }
}
