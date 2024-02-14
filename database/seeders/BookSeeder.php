<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $baseBooks = [
            [
                "category_id" => 1,
                "author_id" => 2,
                "name" => "The Art of War",
                "img" => "image.png",
                "description" => "The art of war is a good book",
                "number_available" => 2
            ]
        ];

        foreach($baseBooks as $book){
            Book::create($book);
        }
    }
}
