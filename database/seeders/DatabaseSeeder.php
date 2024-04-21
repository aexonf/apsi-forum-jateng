<?php

namespace Database\Seeders;

use App\Models\Publication;
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
        Publication::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

         // Seed Users table
        //  DB::table('users')->insert([
        //     'username' => 'admin',
        //     'password' => Hash::make('password'),
        //     'is_password_change' => true,
        //     'role' => 'admin',
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ]);

        // Seed Supervisors table
        // DB::table('supervisors')->insert([
        //     'id_number' => '123456',
        //     'name' => 'John Doe',
        //     'email' => 'john@example2.com',
        //     'phone_number' => '123456789',
        //     'label' => 'A',
        //     'img_url' => 'supervisor.jpg',
        //     'level' => 'SMA',
        //     'user_id' => 1,
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ]);

        // DB::table("discussions")->insert([
        //     "title" => "Discussions",
        //     "content" => "content",
        //     "view_count" => 0,
        //     "status" => "pending",
        //     "supervisor_id" => 1
        // ]);

        DB::table("discussion_comments")->insert([
            "supervisor_id" => 1,
            "discussion_id" => 1,
            "content" => "content",
        ]);
    }
}
