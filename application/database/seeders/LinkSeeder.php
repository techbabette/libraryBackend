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
                "access_level_id" => 3
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
              "text" => "Contact",
              "to" => "Contact",
              "link_position_id" => 1,
              "weight" => 70,
              "access_level_id" => 3
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
                "to" => "https://www.facebook.com/",
                "icon" => "icomoon-free:facebook",
                "link_position_id" => 3,
                "weight" => 100,
                "access_level_id" => 2
            ],
            [
                "text" => "Twitter",
                "to" => "https://www.twitter.com/",
                "icon" => "la:twitter",
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
                "icon" => "bx:sitemap",
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
            ],
            [
                "text" => "Admin",
                "to" => "Admin Dashboard",
                "link_position_id" => 1,
                "weight" => 85,
                "access_level_id" => 4
            ],
            [
                "text" => "Dashboard",
                "to" => "Admin Dashboard",
                "icon" => "material-symbols:dashboard",
                "link_position_id" => 5,
                "weight" => 100,
                "access_level_id" => 4,
            ],
            [
                "text" => "Control Panel",
                "to" => "Admin Control",
                "icon" => "ant-design:control-outlined",
                "link_position_id" => 5,
                "weight" => 95,
                "access_level_id" => 4,
            ],
            [
                "text" => "Logs",
                "to" => "Admin Logs",
                "icon" => "akar-icons:paper",
                "link_position_id" => 5,
                "weight" => 90,
                "access_level_id" => 4
            ],
            [
                "text" => "Back to main site",
                "to" => "Home",
                "icon" => "ri:logout-box-line",
                "link_position_id" => 6,
                "weight" => 100,
                "access_level_id" => 4
            ],
        ];


        foreach($baseLinks as $link){
            Link::create($link);
        }
    }
}
