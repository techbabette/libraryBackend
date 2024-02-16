<?php

namespace Database\Seeders;

use App\Models\Link;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LinkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $baseLinks = [
            [
                "text" => "City Library",
                "to" => "Home",
                "link_position_id" => 2,
                "weight" => 100,
                "access_level_id" => 2
            ],
            [
                "text" => "Home",
                "to" => "Home",
                "link_position_id" => 1,
                "weight" => 100,
                "access_level_id" => 2
            ],
            [
                "text" => "Books",
                "to" => "Books",
                "link_position_id" => 1,
                "weight" => 90,
                "access_level_id" => 2
            ],
            [
                "text" => "Your books",
                "to" => "Your books",
                "link_position_id" => 1,
                "weight" => 89,
                "access_level_id" => 2
            ],
            [
                "text" => "Login",
                "to" => "Login",
                "link_position_id" => 1,
                "weight" => 80,
                "access_level_id" => 1
            ],
            [
                "text" => "Register",
                "to" => "Register",
                "link_position_id" => 1,
                "weight" => 79,
                "access_level_id" => 1
            ],
            [
                "text" => "Author",
                "to" => "Author",
                "link_position_id" => 1,
                "weight" => 0,
                "access_level_id" => 2
            ],
            [
                "text" => "Facebook",
                "to" => "https=>//www.facebook.com/",
                "icon" => "icomoon-free=>facebook",
                "link_position_id" => 3,
                "weight" => 100,
                "access_level_id" => 2
            ],
            [
                "text" => "Twitter",
                "to" => "https=>//www.twitter.com/",
                "icon" => "la=>twitter",
                "link_position_id" => 3,
                "weight" => 90,
                "access_level_id" => 2
            ],
            [
                "text" => "Documentation",
                "to" => "./documentation.pdf",
                "icon" => "fa-file",
                "link_position_id" => 3,
                "weight" => 80,
                "access_level_id" => 2
            ],
            [
                "text" => "Sitemap",
                "to" => "./sitemap.xml",
                "icon" => "bx=>sitemap",
                "link_position_id" => 3,
                "weight" => 70,
                "access_level_id" => 2
            ],
            [
                "text" => "Individual book",
                "to" => "Book preview",
                "link_position_id" => 4,
                "weight" => 0,
                "access_level_id" => 2
            ]
        ];


        foreach($baseLinks as $link){
            Link::create($link);
        }
    }
}
