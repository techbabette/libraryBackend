<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

//$table->foreignId("category_id")->constrained();
//$table->foreignId("author_id")->constrained();
//$table->string("name", 30);
//$table->text("description");
//$table->unsignedInteger("number_available");

    protected $fillable = [
      'category_id',
      'author_id',
      'name',
      'description',
      'number_available'
    ];
}
