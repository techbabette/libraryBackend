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
        'access_level_id',
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
        'email_verified_at' => 'datetime:Y-m-d H:i:s',
        'password' => 'hashed',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function getFullName(){
        return "{$this->name} {$this->last_name}";
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function access_level(){
        return $this->belongsTo(AccessLevel::class);
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
            ["id" => "email_verified_at", 'text' => "Email verified at"],
            ["id" => "access_level.access_level", "text" => "Text"],
        ];
    }

    public function scopeSort($query, string $sortSelected = "created_at_desc"){
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
            case 'email_verified_at':
                $query->orderBy('email_verified_at', $mode);
                break;
            case 'access_level.access_level' : 
                $query->withAggregate('access_level', 'access_level')->orderBy('access_level_access_level', $mode);
            default :
                $query->orderBy('created_at', 'desc');
                break;
        }
    }
}
