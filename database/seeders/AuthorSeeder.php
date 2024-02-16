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
            ],
            [
                'name' => 'Steven',
                'last_name' => 'Pinker'
            ],
            [
                'name' => 'Vaclav',
                'last_name' => 'Smith'
            ],
            [
                "name" => "Wendy",
                "last_name" => "Foster"
            ],
            [
                "name" => "Bart Denton",
                "last_name" => "Ehrman"
            ],
            [
                "name" => "Henrik",
                "last_name" => "Fexeus"
            ]
        ];

        foreach($baseAuthors as $author){
            Author::create($author);
        }
    }
}
