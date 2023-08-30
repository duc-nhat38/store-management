<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::factory()->create([
            'name' => 'User 01',
            'email' => 'user01@gmail.com',
        ]);

        \App\Models\User::factory()->create([
            'name' => 'User 02',
            'email' => 'user02@gmail.com',
        ]);

        \App\Models\User::factory(10)->create();
    }
}
