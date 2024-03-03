<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use SortHelper;

class Link extends Model
{
    use HasFactory;

    protected $fillable = [
      'access_level_id',
      'link_position_id',
      'text',
      'to',
      'icon',
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

    public static function sortOptions(){
        return [
            ["id" => "created_at", "text" => "Recency"],
            ["id" => "icon", "text" => "Icon"],
            ["id" => "weight", "text" => "Weight"],
            ["id" => "text", "text" => "Text"],
            ["id" => "to", "text" => "Leads to"],
            ["id" => "access_level.access_level", "text" => "Text"],
            ["id" => "link_position.position", "text" => "Link position"],
        ];
    }

    public function scopeSort($query, $sortSelected = "created_at_desc"){
        extract(SortHelper::sortOptionAndMode($sortSelected));
        switch($sortOption){
            case 'created_at' :
                $query->orderBy('created_at', $mode);
                break;
            case 'icon' :
                $query->orderBy('icon', $mode);
                break;
            case 'text' :
                $query->orderBy('text', $mode);
                break;
            case 'to' :
                $query->orderBy('to', $mode);
                break;
            case 'weight' :
                $query->orderBy('weight', $mode);
                break;
            case 'access_level.access_level' : 
                $query->withAggregate('access_level', 'access_level')->orderBy('access_level_access_level', $mode);
            case 'link_position.position' : 
                $query->withAggregate('link_position', 'position')->orderBy('link_position_position', $mode);
            default :
                $query->orderBy('created_at', 'desc');
                break;
        }
    }
}
