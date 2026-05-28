<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'midoriau3@gmail.com',
            'password' => Hash::make(env('ADMIN_PASSWORD', 'default_secret_password')),
        ]);
        
        // Let's also create 3 default banner slots so the user has something to edit
        \App\Models\Banner::create(['title' => 'Slot 1', 'image_path' => 'images/promos/ssp.png', 'position' => 1]);
        \App\Models\Banner::create(['title' => 'Slot 2', 'image_path' => 'images/promos/ssp2.png', 'position' => 2]);
        \App\Models\Banner::create(['title' => 'Slot 3', 'image_path' => 'images/promos/surg.png', 'position' => 3]);
    }
}
