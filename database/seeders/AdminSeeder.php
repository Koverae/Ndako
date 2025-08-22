<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\Admin\Models\Admin;
use Str;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::create([
                'name' => 'Super Admin',
                'email' => 'laudbouetoumoussa@koverae.com',
                // 'email_verified_at' => now(),
                'password' => Hash::make('koverae'), // You can change this password
                'remember_token' => \Illuminate\Support\Str::random(10),
            ]
        );

    }
}
