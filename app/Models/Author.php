<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SortHelper;

class Author extends Model
{
    use HasFactory; use SoftDeletes;

    protected $fillable = [
        'name',
        'last_name',
    ];

    public function getFullName(){
        return "{$this->name} {$this->last_name}";
    }
    public function books(){
        return $this->hasMany(Book::class);
    }

    public static function sortOptions(){
        return [
            ["id" => "name", 'text' => "Name"],
            ["id" => "last_name", 'text' => "Last name"],
            ["id" => "books_count", 'text' => "Book count"],
            ["id" => "created_at", 'text' => "Created at"],
        ];
    }

    public function scopeSort($query, string $sortSelected = "books_count_desc"){
        extract(SortHelper::sortOptionAndMode($sortSelected));
        switch($sortOption){
            case 'name' :
                $query->orderBy('name', $mode);
                break;
            case 'last_name' :
                $query->orderBy('last_name', $mode);
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
