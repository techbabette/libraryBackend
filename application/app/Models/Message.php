<?php

namespace App\Models;

use App\Models\MessageType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use SortHelper;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'message_type_id',
        'title',
        'body'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function message_type(){
        return $this->belongsTo(MessageType::class);
    }

    public static function sortOptions(){
        return [
            ["id" => "title", 'text' => "Title"],
            ["id" => "body", 'text' => "Body"],
            ['id' => 'created_at', "text" => "Created at"],
            ["id" => "message_type.name", "text" => "Text"],
            ["id" => "user.email", "text" => "Text"],
        ];
    }

    public function scopeSort($query, string $sortSelected = "created_at_desc"){
        extract(SortHelper::sortOptionAndMode($sortSelected));
        switch($sortOption){
            case 'title' :
                $query->orderBy('title', $mode);
                break;
            case 'body':
                $query->orderBy('body', $mode);
                break;
            case 'created_at':
                $query->orderBy('created_at', $mode);
                break;
            case 'email_verified_at':
                $query->orderBy('email_verified_at', $mode);
                break;
            case 'message_type.name' : 
                $query->withAggregate('message_type', 'name')->orderBy('message_type_name', $mode);
            case 'user.email' : 
                $query->withAggregate('user', 'email')->orderBy('user_email', $mode);
            default :
                $query->orderBy('created_at', 'desc');
                break;
        }
    }
}
