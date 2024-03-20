<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SortHelper;

class Category extends Model
{
    use HasFactory; use SoftDeletes;

    protected $fillable = [
        'text',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function books(){
        return $this->hasMany(Book::class);
    }

    public function allBooks(){
        return $this->hasMany(Book::class)->withTrashed();
    }
    
    //Get total loan count, including inactive books and inactive loans
    public function loans(){
        return $this->hasManyThrough(Loan::class, Book::class)->withTrashedParents()->withTrashed();
    }

    public function activeLoans(){
        return $this->hasManyThrough(Loan::class, Book::class)->withTrashedParents();
    }

    public function newLoans(){
        return $this->activeLoans()->new();
    }

    public function lateLoans(){
        return $this->activeLoans()->late();
    }

    public function delete(){
        $this->books->each->delete();

        return parent::delete();
    }

    public static function sortOptions(){
        return [
          ["id" => "text", 'text' => "Category name"],
          ["id" => "books_count", 'text' => "Active book count"],
          ["id" => "all_books_count", 'text' => "Associated books count"],
          ["id" => "active_loans_count", 'text' => "Current loans"],
          ["id" => "loans_count", 'text' => "Total loans"],
          ["id" => "created_at", 'text' => "Created at"],
        ];
    }

    public function scopeSort($query, string $sortSelected = "books_count_desc"){
        extract(SortHelper::sortOptionAndMode($sortSelected));
        switch($sortOption){
            case 'text' :
                $query->orderBy('text', $mode);
                break;
            case 'books_count' :
                $query->withCount('books')->orderBy('books_count', $mode);
                break;
            case 'all_books_count' : 
                $query->withCount('allBooks')->orderBy('all_books_count', $mode);
            case 'active_loans_count' : 
                $query->withCount('activeLoans')->orderBy('active_loans_count', $mode);
            case 'loans_count' : 
                $query->withCount('loans')->orderBy('loans_count', $mode);
            case 'created_at':
                $query->orderBy('created_at', $mode);
                break;
            default :
                $query->orderBy('created_at', 'desc');
                break;
        }
    }
}
