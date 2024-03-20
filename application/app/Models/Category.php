<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'text',
    ];

    public function loans(){
        return $this->hasManyThrough(Loan::class, Book::class)->withTrashed();
    }

    public function activeLoans(){
        return $this->hasManyThrough(Loan::class, Book::class);
    }

    public function newLoans(){
        return $this->activeLoans()->new();
    }

    public function lateLoans(){
        return $this->activeLoans()->late();
    }

    public function books(){
        return $this->hasMany(Book::class);
    }

    public static function sortOptions(){
        return [
          ["id" => "text", 'text' => "Category name"],
          ["id" => "books_count", 'text' => "Book count"],
          ["id" => "created_At", 'text' => "Created at"],
        ];
    }

    public function scopeSort($query, string $sortSelected = "books_count_desc"){
        $base = explode("_", $sortSelected);
        $mode = array_pop($base);
        $allowedModes = ["asc", "desc"];
        if(!in_array($mode, $allowedModes)){
            return;
        }
        $baseString = implode('_', $base);
        switch($baseString){
            case 'text' :
                $query->orderBy('text', $mode);
                break;
            case 'books_count' :
                $query->withCount('books')->orderBy('books_count', $mode);
                break;
            case 'created_at':
                $query->orderBy('created_at', $mode);
                break;
            default :
                $query->orderBy('created_at', 'desc');
                break;
        }
    }
}
