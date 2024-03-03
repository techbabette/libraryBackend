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

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s',
    ];

    protected $appends = ['full_name'];

    public function getFullNameAttribute()
    {
        // Concatenate first_name and last_name with a space in between
        return $this->attributes['name'] . ' ' . $this->attributes['last_name'];
    }

    public function setFullNameAttribute($value){
        return $this->full_name = $value;
    }

    public function getFullName(){
        return "{$this->name} {$this->last_name}";
    }
    public function books(){
        return $this->hasMany(Book::class);
    }

    public function allBooks(){
        return $this->hasMany(Book::class)->withTrashed();
    }

    public function loans(){
        return $this->hasManyThrough(Loan::class, Book::class)->withTrashedParents()->withTrashed();
    }

    public function activeLoans(){
        return $this->hasManyThrough(Loan::class, Book::class)->withTrashedParents();
    }

    public function delete(){
        $this->books->each->delete();

        return parent::delete();
    }

    public static function sortOptions(){
        return [
            ["id" => "name", 'text' => "Name"],
            ["id" => "last_name", 'text' => "Last name"],
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
            case 'name' :
                $query->orderBy('name', $mode);
                break;
            case 'last_name' :
                $query->orderBy('last_name', $mode);
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
