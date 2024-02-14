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
                "position" => "header",
                "weight" => 100,
                "access_level_id" => 2
            ],
            [
                "text" => "Home",
                "to" => "Home",
                "position" => "navbar",
                "weight" => 100,
                "access_level_id" => 2
            ],
            [
                "text" => "Books",
                "to" => "Books",
                "position" => "navbar",
                "weight" => 90,
                "access_level_id" => 2
            ],
            [
                "text" => "Your books",
                "to" => "Your books",
                "position" => "navbar",
                "weight" => 89,
                "access_level_id" => 2
            ],
            [
                "text" => "Login",
                "to" => "Login",
                "position" => "navbar",
                "weight" => 80,
                "access_level_id" => 1
            ],
            [
                "text" => "Register",
                "to" => "Register",
                "position" => "navbar",
                "weight" => 79,
                "access_level_id" => 1
            ],
            [
                "text" => "Author",
                "to" => "Author",
                "position" => "navbar",
                "weight" => 0,
                "access_level_id" => 2
            ],
            [
                "text" => "Facebook",
                "to" => "https=>//www.facebook.com/",
                "icon" => "icomoon-free=>facebook",
                "position" => "footer",
                "weight" => 100,
                "access_level_id" => 2
            ],
            [
                "text" => "Twitter",
                "to" => "https=>//www.twitter.com/",
                "icon" => "la=>twitter",
                "position" => "footer",
                "weight" => 90,
                "access_level_id" => 2
            ],
            [
                "text" => "Documentation",
                "to" => "./documentation.pdf",
                "icon" => "fa-file",
                "position" => "footer",
                "weight" => 80,
                "access_level_id" => 2
            ],
            [
                "text" => "Sitemap",
                "to" => "./sitemap.xml",
                "icon" => "bx=>sitemap",
                "position" => "footer",
                "weight" => 70,
                "access_level_id" => 2
            ],
            [
                "text" => "Individual book",
                "to" => "Book preview",
                "position" => "hidden",
                "weight" => 0,
                "access_level_id" => 2
            ]
        ];


        foreach($baseLinks as $link){
            Link::create($link);
        }
    }
}
