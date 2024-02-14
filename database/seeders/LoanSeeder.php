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
                "book_id" => 1,
                "user_id" => 1,
            ]
        ];

        foreach($baseLoans as $loan){
            Loan::create($loan);
        }
    }
}
