<?php

namespace App\Models;

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
        switch($sortSelected){
            case 'issuer_desc':
                $query->orderBy('issuer', 'desc');
                break;
            case 'issuer_asc' :
                $query->orderBy('issuer', 'asc');
                break;
            case 'created_at_desc':
                $query->orderBy('created_at', 'desc');
                break;
            case 'created_at_asc':
                $query->orderBy('created_at', 'asc');
                break;
            case 'action_desc':
                $query->orderBy('action', 'desc');
                break;
            case 'action_asc':
                $query->orderBy('action', 'asc');
                break;
            default :
                $query->orderBy('created_at', 'desc');
                break;
        }
    }
}
