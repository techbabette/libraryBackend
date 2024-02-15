<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    protected $fillable = [
        'access_level_id',
        'name',
    ];

    public function access_level() {
        return $this->belongsTo(AccessLevel::class);
    }
}
