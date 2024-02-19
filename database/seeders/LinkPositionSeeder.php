<?php

namespace Database\Seeders;

use App\Models\LinkPosition;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LinkPositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $baseLinkPositions = [
            [
                "position" => "navbar"
            ],
            [
                "position" => "header"
            ],
            [
                "position" => "footer"
            ],
            [
                "position" => "hidden"
            ],
            [
                "position" => "adminNavbar"
            ],
            [
                "position" => "adminFooter"
            ]
        ];


        foreach($baseLinkPositions as $linkPosition){
            LinkPosition::create($linkPosition);
        }
    }
}
