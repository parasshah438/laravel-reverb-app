<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = [
            ['name' => 'Alice Smith',    'email' => 'alice@chat.com'],
            ['name' => 'Bob Johnson',    'email' => 'bob@chat.com'],
            ['name' => 'Charlie Davis',  'email' => 'charlie@chat.com'],
            ['name' => 'Diana Wilson',   'email' => 'diana@chat.com'],
            ['name' => 'Evan Brown',     'email' => 'evan@chat.com'],
        ];

        foreach ($users as $data) {
            User::firstOrCreate(
                ['email' => $data['email']],
                ['name' => $data['name'], 'password' => bcrypt('password'), 'email_verified_at' => now()]
            );
        }
    }
}
