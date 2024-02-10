<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'last_name',
    ];

    public function getFullName(){
        return "{$this->name} {$this->last_name}";
    }
}
