<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Category;

class Book extends Model
{
    use HasFactory; use SoftDeletes;

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
            ["id" => "created_at", "text" => "Recency"],
            ["id" => "name", "text" => "Book title"],
            ["id" => "all_loans_count", "text" => "Popularity"],
        ];
    }
    public function scopeSort(Builder $query, $sortSelected = "created_at_desc"){
        $base = explode("_", $sortSelected);
        $mode = array_pop($base);
        $allowedModes = ["asc", "desc"];
        if(!in_array($mode, $allowedModes)) {
            return;
        }
        $baseString = implode('_', $base);
        switch($baseString) {
            case 'created_at' :
                $query->orderBy('created_at', $mode);
                break;
            case 'name' :
                $query->orderBy('name', $mode);
                break;
            case 'all_loans_count':
                $query->withCount('allLoans')->orderBy('all_loans_count', $mode);
                break;
            default :
                $query->orderBy('created_at', 'desc');
                break;
        }
        }
}
