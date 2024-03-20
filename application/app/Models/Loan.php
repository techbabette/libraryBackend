<?php

namespace App\Models;

use SortHelper;
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
            ["id" => 'extended', "text" => 'Extended'],
            ['id' => 'user.email', 'text' => 'User email'],
            ['id' => 'book.name', 'text' => 'Book name'],
            ["id" => 'end', "text" => "Return by"],
            ["id" => 'started_at', "text" => "Started at"],
            ["id" => 'returned_at', "text" => 'Returned at']
        ];
    }

    public function scopeSort($query, string $sortSelected = "started_at_desc"){
        extract(SortHelper::sortOptionAndMode($sortSelected));
        switch($sortOption){
            case 'returned_at' :
                $query->orderBy('deleted_at', $mode);
                break;
            case 'extended' :
                $query->orderBy('extended', $mode);
                break;
            case 'user.email':
                $query->withAggregate('user', 'email')->orderBy('user_email', $mode);
            case 'book.name':
                $query->withAggregate('book', 'name')->orderBy('book_name', $mode);
            case 'end':
                $query->orderBy('end', $mode);
                break;
            case 'started_at':
                $query->orderBy('created_at', $mode);
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
