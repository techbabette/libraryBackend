<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $baseCategories = [["text" => "History"], ["text" => "Sci-Fi"], ['text' => 'Popular science'], ['text' => 'Linguistics'], ['text' => 'Popular Psychology']];

        foreach($baseCategories as $category){
            Category::create($category);
        }

    }
}
