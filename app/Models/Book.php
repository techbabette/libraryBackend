<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
class Book extends Model
{
    use HasFactory;

//$table->foreignId("category_id")->constrained();
//$table->foreignId("author_id")->constrained();
//$table->string("name", 30);
//$table->text("description");
//$table->unsignedInteger("number_owned");

    protected $fillable = [
      'category_id',
      'author_id',
      'name',
      'description',
      'number_owned',
      'img'
    ];

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function author(){
        return $this->belongsTo(Author::class);
    }


    public function allLoans(){
        return $this->hasMany(Loan::class)->withTrashed();
    }
    public function loans(){
        return $this->hasMany(Loan::class);
    }

    public function loanTotalCount(){
        return $this->allLoans->count();
    }

    public function loansCurrentCount(){
        return $this->loans->count();
    }

    public function currentlyAvailable(){
        return $this->number_owned - $this->loansCurrentCount();
    }

    public function loanedToCurrentUser(){
        $userId = 0;
        if(auth()->user()){
            $userId = auth()->user()->id;
        }
        $activeLoan = $this->loans->where('user_id', '=', $userId)->first();
        if($activeLoan){
            return $activeLoan->id;
        }
        else{
            return false;
        }
    }

    public static function sortOptions(){
        return [
            ["id" => 0, "text" => "Newest first"],
            ["id" => 1, "text" => "Oldest first"],
            ["id" => 2, "text" => "Z-A"],
            ["id" => 3, "text" => "A-Z"],
            ["id" => 4, "text" => "Most popular"],
            ["id" => 5, "text" => "Least popular"],
        ];
    }
    public static function sort(Builder $query, $selectedOption){
        if($selectedOption === "0"){
            $query->orderByDesc("created_at");
        }

        if($selectedOption === "1"){
            $query->orderBy("created_at");
        }

        if($selectedOption === "2"){
            $query->orderByDesc("name");
        }

        if($selectedOption === "3"){
            $query->orderBy("name", 'asc');
        }

        if($selectedOption === "4"){
            $query->withCount('allLoans')->orderByDesc('all_loans_count');
        }

        if($selectedOption === "5"){
            $query->withCount('allLoans')->orderBy('all_loans_count');
        }

        return $query;
    }
}
