<?php

namespace Database\Seeders;

use App\Models\Author;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $baseAuthors = [
            [
                "name" => "Yuval Noah",
                "last_name" => "Harari"
            ],
            [
                'name' => 'Sun',
                'last_name' => 'Tzu'
            ],
            [
                'name' => 'Stephen',
                'last_name' => 'King'
            ]
        ];

        foreach($baseAuthors as $author){
            Author::create($author);
        }
    }
}
