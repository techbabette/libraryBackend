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
                "img" => "the_art_of_war.jpg",
                "description" => "The art of war is a good book",
                "number_owned" => 2
            ],
            [
                "category_id" => 1,
                "author_id" => 1,
                "name" => "Sapiens",
                "img" => "sapiens.jpg",
                "description" => "A historical overview of human evolution and civilization, addressing how humans became the dominant species and shaped their societies, economies, and cultures",
                "number_owned" => 2
            ],
            [
                "category_id" => 3,
                "author_id" => 1,
                "name" => "Homo Deus",
                "img" => "homo_deus.jpg",
                "description" => " Homo Deus explores the projects, dreams and nightmares that will shape the twenty-first century—from overcoming death to creating artificial life. It asks the fundamental questions: Where do we go from here? And how will we protect this fragile world from our own destructive powers? This is the next stage of evolution. This is Homo Deus.",
                "number_owned" => 2
            ],
            [
                "category_id" => 5,
                "author_id" => 8,
                "name" => "The Art of Reading Minds",
                "img" => "the_art_of_reading_minds.jpg",
                "description" => "How would you like to know what the people around you are thinking? Do you want to network like a pro, persuade your boss to give you that promotion, and finally become the life of every party? Now, with Henrik Fexeus's expertise, you can. ",
                "number_owned" => 2
            ],
            [
                "category_id" => 5,
                "author_id" => 8,
                "name" => "The Art of Social Excellence",
                "img" => "the_art_of_social_excellence.jpg",
                "description" => "Henrik teaches a type of social skill reserved for a privileged few – until now. People with social finesse are more often appointed leaders, gets promoted faster and can quickly create meaningful and deep relationships with others. ",
                "number_owned" => 2
            ],
            [
                "category_id" => 3,
                "author_id" => 5,
                "name" => "Numbers don't lie",
                "img" => "numbers_dont_lie.jpg",
                "description" => "Vaclav Smil's mission is to make facts matter. An environmental scientist, policy analyst, and a hugely prolific author, he is Bill Gates' go-to guy for making sense of our world. In Numbers Don't Lie, Smil answers questions such as: What's worse for the environment--your car or your phone? How much do the world's cows weigh (and what does it matter)? And what makes people happy? ",
                "number_owned" => 3
            ],
        ];

        foreach($baseBooks as $book){
            Book::create($book);
        }
    }
}
