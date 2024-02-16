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
            ],
            [
                "category_id" => 1,
                "author_id" => 1,
                "name" => "Sapiens",
                "img" => "image.png",
                "description" => "A historical overview of human evolution and civilization, addressing how humans became the dominant species and shaped their societies, economies, and cultures",
                "number_available" => 2
            ],
            [
                "category_id" => 3,
                "author_id" => 1,
                "name" => "Homo Deus",
                "img" => "image.png",
                "description" => " Homo Deus explores the projects, dreams and nightmares that will shape the twenty-first century—from overcoming death to creating artificial life. It asks the fundamental questions: Where do we go from here? And how will we protect this fragile world from our own destructive powers? This is the next stage of evolution. This is Homo Deus.",
                "number_available" => 2
            ],
            [
                "category_id" => 5,
                "author_id" => 8,
                "name" => "The Art of Reading Minds",
                "img" => "image.png",
                "description" => "How would you like to know what the people around you are thinking? Do you want to network like a pro, persuade your boss to give you that promotion, and finally become the life of every party? Now, with Henrik Fexeus's expertise, you can. ",
                "number_available" => 2
            ],
            [
                "category_id" => 5,
                "author_id" => 8,
                "name" => "The Art of Social Excellence",
                "img" => "image.png",
                "description" => "Henrik teaches a type of social skill reserved for a privileged few – until now. People with social finesse are more often appointed leaders, gets promoted faster and can quickly create meaningful and deep relationships with others. ",
                "number_available" => 2
            ],
        ];

        foreach($baseBooks as $book){
            Book::create($book);
        }
    }
}
