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
}
