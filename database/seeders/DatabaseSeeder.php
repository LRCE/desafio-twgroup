<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'administrador',
            'last_name' => 'twgroup',
            'email' => 'admin@twgroup.cl',
            'password' => 'password',
            'is_admin' => true,
        ]);
    }
}
