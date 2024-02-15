<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    use HasFactory;

    protected $fillable = [
      'access_level_id',
      'text',
      'to',
      'icon',
      'position',
      'weight'
    ];

    public function access_level(){
        return $this->belongsTo(AccessLevel::class);
    }

    public static function getLinksForAccessLevel(int $accessLevel){
        $links = Link::whereHas('access_level', function ($query) use ($accessLevel) {
            if($accessLevel > 0){
                $query->where('access_level', '<=', $accessLevel);
                $query->where('access_level', '<>', -1);
            }
            else{
                $query->where('access_level', '<=', 2);
            }
        })->get();

        return $links;
    }
}
