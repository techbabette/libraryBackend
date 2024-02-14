<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $baseUsers = [
            [
                'name' => 'First',
                'last_name' => 'Last',
                'email' => 'email@gmail.com',
                'password' => 'password',
                'address' => 'My address 123'
            ]
        ];

        foreach($baseUsers as $user){
            User::create($user);
        }
    }
}
