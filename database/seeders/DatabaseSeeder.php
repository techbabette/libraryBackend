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
        $this->call([
            AccessLevelSeeder::class,
            LinkSeeder::class,
            RoleSeeder::class,
            CategorySeeder::class,
            AuthorSeeder::class,
            BookSeeder::class,
            UserSeeder::class,
            FavoriteSeeder::class,
            LoanSeeder::class,
            MessageTypeSeeder::class
        ]);
    }
}
