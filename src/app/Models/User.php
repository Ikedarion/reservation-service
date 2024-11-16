<?php

namespace App\Models;

use App\Imports\RestaurantsImport;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
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
    ];


    public function restaurant()
    {
        return $this->hasOne(Restaurant::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function favoriteRestaurants()
    {
        return $this->belongsToMany(Restaurant::class, 'favorites');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }




    public function scopeKeywordSearch($query,$keyword) {
        if(!empty($keyword)) {
            $query->where('name', 'like' ,'%' . $keyword . '%')
                ->orWhere('email','like','%' . $keyword . '%')
                ->orWhereHas('restaurant',function ($query) use ($keyword) {
                    $query->where('name','like' ,'%' . $keyword . '%');
                });
        }
        return $query;
    }
}
