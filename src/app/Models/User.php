<?php

namespace App\Models;

use App\Imports\RestaurantsImport;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Cashier\Billable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Notifications\VerifyEmailNotification;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, Billable;

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmailNotification);
    }

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
        'stripe_id',
        'pm_type',
        'pm_last_four',
        'trial_ends_at',
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

    public function scopeUserSearch($query, $keyword)
    {
        if (!empty($keyword)) {
            $query->where('name', 'like', '%' . $keyword . '%')
                ->orWhere('email', 'like', '%' . $keyword . '%')
                ->orWhere('role', 'like', '%' . $keyword . '%');
        }
        return $query;
    }
}
