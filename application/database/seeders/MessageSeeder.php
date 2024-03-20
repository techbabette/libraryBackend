<?php

namespace Database\Seeders;

use App\Models\Message;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $baseMessages = [
            [
                'message_type_id' => 3,
                'title' => 'Hello',
                'body' => 'Hello how are you'
            ],
            [
                'message_type_id' => 1,
                'title' => 'Issue',
                'body' => 'Hello I have an issue'
            ]
        ];

        foreach($baseMessages as $message){
            Message::create($message);
        }
    }
}
