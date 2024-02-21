<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Loan extends Model
{
    use HasFactory; use SoftDeletes;

    protected $fillable = [
        'book_id',
        'user_id',
        'start',
        'end',
        "extended"
    ];
    protected $maps = [
        "created_at" => "started_at",
        "deleted_at" => 'returned_at'
    ];

    protected $appends = ['started_at', 'returned_at'];

    protected $hidden = ['created_at', 'deleted_at'];

    public function getStartedAtAttribute(){
        return $this->attributes['created_at'];
    }

    public function getReturnedAtAttribute(){
        return $this->attributes['deleted_at'];
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function book(){
        return $this->belongsTo(Book::class);
    }

    public static function sortOptions(){
        return [
            ["id" => 'end', "text" => "Action"],
            ["id" => 'started_at', "text" => "Started at", "default" => "desc"],
        ];
    }

    public function scopeSort($query, string $sortSelected){
        switch($sortSelected){
            case 'end_desc':
                $query->orderBy('end', 'desc');
                break;
            case 'end_asc' :
                $query->orderBy('end', 'asc');
                break;
            case 'started_at_desc':
                $query->orderBy('created_at', 'desc');
                break;
            case 'started_at_asc':
                $query->orderBy('created_at', 'asc');
                break;
            default :
                $query->orderBy('created_at', 'desc');
                break;
        }
    }

    public function scopeNew($query){
        $query->where('loans.created_at', '>=', Carbon::now()->addDays(-1));
    }
    public function scopeLate($query){
        $query->where('end', '<=', Carbon::now());
    }

    public static function create(array $attributes)
    {
        $attributes['end'] = Carbon::now()->addDays(20);

        $model = static::query()->create($attributes);
        return $model;
    }
}
