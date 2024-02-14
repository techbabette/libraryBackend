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
        $baseAccessLevels = [["name" => "logged out", 'access_level' => -1], ['name' => 'standard', 'access_level' => 0], ['name' => 'admin', 'access_level' => 1]];
        foreach($baseAccessLevels as $accessLevel){
            AccessLevel::create($accessLevel);
        }
    }
}
