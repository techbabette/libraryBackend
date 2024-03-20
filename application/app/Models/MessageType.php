<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use SortHelper;

class MessageType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function messages(){
        return $this->hasMany(Message::class);
    }

    public static function sortOptions(){
        return [
            ["id" => "name", 'text' => "Message type"],
            ["id" => "created_at", 'text' => "Created at"],
            ["id" => "messages_count", "text" => "Text"],
        ];
    }

    public function scopeSort($query, string $sortSelected = "created_at_desc"){
        extract(SortHelper::sortOptionAndMode($sortSelected));
        switch($sortOption){
            case 'name' :
                $query->orderBy('name', $mode);
                break;
            case 'created_at':
                $query->orderBy('created_at', $mode);
                break;
            case 'messages_count' : 
                $query->withCount('messages')->orderBy('messages_count', $mode);
            default :
                $query->orderBy('created_at', 'desc');
                break;
        }
    }
}
