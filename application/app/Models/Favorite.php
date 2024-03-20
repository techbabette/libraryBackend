<?php

namespace App\Models;

use SortHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'user_id',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function book(){
        return $this->belongsTo(Book::class);
    }

    public static function sortOptions(){
        return [
            ['id' => 'book.name', 'text' => 'Book name'],
            ['id' => 'user.email', 'text' => 'User email'],
            ["id" => 'created_at', "text" => "Added to favorites at"],
        ];
    }

    public function scopeSort($query, string $sortSelected = "started_at_desc"){
        extract(SortHelper::sortOptionAndMode($sortSelected));
        switch($sortOption){
            case 'user.email':
                $query->withAggregate('user', 'email')->orderBy('user_email', $mode);
            case 'book.name':
                $query->withAggregate('book', 'name')->orderBy('book_name', $mode);
            case 'created_at':
                $query->orderBy('created_at', $mode);
                break;
            default :
                $query->orderBy('created_at', 'desc');
                break;
        }
    }
}
