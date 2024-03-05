<?php

namespace Database\Seeders;

use App\Models\Loan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LoanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $baseLoans = [
            [
                "book_id" => 2,
                "user_id" => 1,
            ],
            [
                "book_id" => 3,
                "user_id" => 1,
            ],
            [
                "book_id" => 4,
                "user_id" => 1,
            ],
            [
                "book_id" => 5,
                "user_id" => 1,
            ],
            [
                "book_id" => 6,
                "user_id" => 1,
            ],
            [
                "book_id" => 6,
                "user_id" => 2,
            ],
            [
                "book_id" => 5,
                "user_id" => 2,
            ],
            [
                "book_id" => 1,
                "user_id" => 3
            ],
            [
                "book_id" => 2,
                "user_id" => 3
            ]
        ];

        foreach($baseLoans as $loan){
            Loan::create($loan);
        }
    }
}
