<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    use HasFactory;

    protected $fillable = [
      'access_level_id',
      'link_position_id',
      'text',
      'to',
      'icon',
      'position',
      'weight'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function access_level(){
        return $this->belongsTo(AccessLevel::class);
    }

    public function link_position(){
        return $this->belongsTo(LinkPosition::class);
    }

    public static function getLinksForAccessLevel(int $accessLevel){
        $links = Link::whereHas('access_level', function ($query) use ($accessLevel) {
            //If logged in, show links everyone can see, links that require you to be logged in, but not
            // links specific to logged out people
            if($accessLevel > 0){
                $query->where('access_level', '<=', $accessLevel);
                $query->where('access_level', '<>', -1);
            }
            //If not logged in, show links everyone can see
            else{
                $query->where('access_level', '<=', 0);
            }
        })->join('link_positions', 'links.link_position_id', '=', 'link_positions.id')
           ->get(["text", "to", "icon", "weight", "position"]);

        return $links;
    }
}
