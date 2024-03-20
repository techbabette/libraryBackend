<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $baseRoles = [
          [
              "name" => "Banned",
              "access_level_id" => 1
          ],
          [
              "name" => "Standard",
              "access_level_id" => 3
          ],
          [
              "name" => "Admin",
              "access_level_id" => 4
          ],
        ];

        foreach($baseRoles as $role){
            Role::create($role);
        }
    }
}
