<?php

namespace App\Models;

use SortHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    protected $fillable = [
      'issuer',
      'action'
    ];

    public static function sortOptions(){
        return [
            ["id" => 'issuer', "text" => "Issuer"],
            ["id" => 'action', "text" => "Action"],
            ["id" => 'created_at', "text" => "Issued at", "default" => "desc"],
        ];
    }

    public function scopeSort($query, string $sortSelected){
        extract(SortHelper::sortOptionAndMode($sortSelected));
        switch($sortOption){
            case 'issuer':
                $query->orderBy('issuer', $mode);
                break;
            case 'created_at':
                $query->orderBy('created_at', $mode);
                break;
            case 'action':
                $query->orderBy('action', $mode);
                break;
            default :
                $query->orderBy('created_at', 'desc');
                break;
        }
    }
}
