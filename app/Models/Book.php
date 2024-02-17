<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

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
      'number_available',
      'img'
    ];

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function author(){
        return $this->belongsTo(Author::class);
    }

    public static function sortOptions(){
        return [["id" => 0, "text" => "Newest first"], ["id" => 1, "text" => "Oldest first"]];
    }
    public static function sort(Builder $query, $selectedOption){
        if($selectedOption === 0){
            $query->orderByDesc("created_at");
        }

        if($selectedOption === 1){
            $query->orderBy("created_at");
        }

        if($selectedOption === 2){
            $query->orderBy("name");
        }

        if($selectedOption === 3){
            $query->orderByDesc("name");
        }

        return $query;
    }
}
