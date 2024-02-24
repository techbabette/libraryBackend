<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

use SortHelper;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'role_id',
        'last_name',
        'email',
        'password',
        'address'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getFullName(){
        return "{$this->name} {$this->last_name}";
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function role(){
        return $this->belongsTo(Role::class);
    }

    public function access_level(){
        return $this->role->belongsTo(AccessLevel::class);
    }

    public function loans(){
        return $this->hasMany(Loan::class)->withTrashed();
    }

    public function linksForUser(){
        $userAccessLevel = $this->access_level->access_level;
        $links = Link::getLinksForAccessLevel($userAccessLevel);
        return $links;
    }

    public function getJWTCustomClaims()
    {
        return ['links' => $this->linksForUser(),
            'access_level' => $this->access_level->access_level,
            'name' => $this->getFullName()
        ];
    }


    public static function sortOptions(){
        return [
            ["id" => "email", 'text' => "Email"],
            ["id" => "loans_count", 'text' => "Loan count"],
            ["id" => "created_at", 'text' => "Created at"],
        ];
    }

    public function scopeSort($query, string $sortSelected = "books_count_desc"){
        extract(SortHelper::sortOptionAndMode($sortSelected));
        switch($sortOption){
            case 'email' :
                $query->orderBy('email', $mode);
                break;
            case 'loans_count' :
                $query->withCount('loans')->orderBy('loans_count', $mode);
                break;
            case 'created_at':
                $query->orderBy('created_at', $mode);
                break;
            default :
                $query->orderBy('created_at', 'desc');
                break;
        }
    }
}
