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
                'access_level_id' => '4',
                'name' => 'First',
                'last_name' => 'Last',
                'email' => 'ilija.krstic.155.21@ict.edu.rs',
                'password' => 'password',
                'address' => 'My address 123',
                'email_verified_at' => now()
            ],
            [
                'access_level_id' => '3',
                'name' => 'Name',
                'last_name' => 'Lastname',
                'email' => 'email2@gmail.com',
                'password' => 'password',
                'address' => 'My address 123',
                'email_verified_at' => now()
            ],
            [
                'access_level_id' => '3',
                'name' => 'Name',
                'last_name' => 'Lastname',
                'email' => 'email3@gmail.com',
                'password' => 'password',
                'address' => 'My address 123',
                'email_verified_at' => now()
            ]
        ];

        foreach($baseUsers as $user){
            User::create($user);
        }
    }
}
