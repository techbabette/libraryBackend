<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Category;
use SortHelper;

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

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function category(){
        return $this->belongsTo(Category::class)->withTrashed();
    }

    public function author(){
        return $this->belongsTo(Author::class)->withTrashed();
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

    public function favorites(){
        return $this->hasMany(Favorite::class);
    }

    public function currentlyAvailable(){
        return $this->number_owned - $this->loansCurrentCount();
    }

    public function loanToCurrentUser(){
        $userId = 0;
        if(auth()->user()){
            $userId = auth()->user()->id;
        }
        return $this->hasMany(Loan::class)->where('user_id', $userId)->first();
    }

    public function favoriteToCurrentUser(){
        $userId = 0;
        if(auth()->user()){
            $userId = auth()->user()->id;
        }
        return $this->hasMany(Favorite::class)->where('user_id', $userId)->first();
    }

    public static function sortOptions(){
        return [
            ["id" => "created_at", "text" => "Recency"],
            ["id" => "name", "text" => "Book title"],
            ["id" => "all_loans_count", "text" => "Popularity"],
            ["id" => "loans_count", "text" => "!!Current loans"],
            ["id" => "category.text", "text" => "!!Category name"],
            ["id" => "author.full_name", "text" => "!!Author name"],
        ];
    }
    public function scopeSort(Builder $query, $sortSelected = "created_at_desc"){
        extract(SortHelper::sortOptionAndMode($sortSelected));
        switch($sortOption){
            case 'created_at' :
                $query->orderBy('created_at', $mode);
                break;
            case 'name' :
                $query->orderBy('name', $mode);
                break;
            case 'category.text' :
                $query->withAggregate('category', 'text')->orderBy('category_text', $mode);
                break;
            case 'author.full_name' :
                $query->withAggregate('author', 'name')->orderBy('author_name', $mode);
                $query->withAggregate('author', 'last_name')->orderBy('author_last_name', $mode);
                break;
            case 'loans_count':
                $query->withCount('loans')->orderBy('loans_count', $mode);
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
