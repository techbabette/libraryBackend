<?php

namespace Database\Seeders;

use App\Models\AccessLevel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccessLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $baseAccessLevels = [["name" => "Logged out", 'access_level' => -1], ['name' => 'Everyone', 'access_level' => 0], ['name' => 'Logged in', 'access_level' => 1], ['name' => 'Admin', 'access_level' => 2]];
        foreach($baseAccessLevels as $accessLevel){
            AccessLevel::create($accessLevel);
        }
    }
}
