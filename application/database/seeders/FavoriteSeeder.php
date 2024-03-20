<?php

namespace Database\Seeders;

use App\Models\Favorite;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FavoriteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $baseFavorites = [
            [
                "book_id" => 1,
                "user_id" => 1
            ],
            [
                "book_id" => 2,
                "user_id" => 2
            ],
            [
                "book_id" => 3,
                "user_id" => 2
            ]
        ];

        foreach($baseFavorites as $favorite){
            Favorite::create($favorite);
        }
    }
}
