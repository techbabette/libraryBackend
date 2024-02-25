<?php

namespace App\Models;

use App\Models\MessageType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
