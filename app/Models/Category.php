<?php

namespace App\Models;

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

    public function books(){
        return $this->hasMany(Book::class);
    }
}
