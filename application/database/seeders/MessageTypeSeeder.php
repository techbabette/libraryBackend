<?php

namespace Database\Seeders;

use App\Models\MessageType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MessageTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $baseMessageTypes = [
            [
                'name' => 'Error'
            ],
            [
                'name' => 'Inquiry'
            ],
            [
                'name' => 'General'
            ]
        ];

        foreach($baseMessageTypes as $messageType){
            MessageType::create($messageType);
        }
    }
}
