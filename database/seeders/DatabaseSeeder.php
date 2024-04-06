<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

         // Seed Users table
         DB::table('users')->insert([
            'username' => 'admin',
            'password' => Hash::make('password'),
            'is_password_change' => false,
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Seed Supervisors table
        DB::table('supervisors')->insert([
            'id_number' => '123456',
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone_number' => '123456789',
            'label' => 'A',
            'img_url' => 'supervisor.jpg',
            'level' => 'SMA',
            'user_id' => 4,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
