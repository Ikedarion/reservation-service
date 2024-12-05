<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name','description', 'address', 'genre', 'tell', 'image', 'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function favoriteUsers()
    {
        return $this->belongsToMany(User::class,'favorites');
    }




    public function getAverageRatingAttribute()
    {
        $reviews = $this->reservations->map(function($reservation) {
            return $reservation->review;
        })->filter();
        return $reviews->isNotEmpty() ? $reviews->avg('rating') : 0;
    }

    public function scopeAreaSearch($query,$area)
    {
        if(!empty($area)) {
            $query->where('address','like' , '%' . $area . '%');
        }
        return $query;
    }

    public function scopeGenreSearch($query,$genre)
    {
        if(!empty($genre)) {
            $query->where('genre',$genre);
        }
        return $query;
    }

    public function scopeKeywordSearch($query,$keyword)
    {
        if(!empty($keyword)) {
            $query->where('name' , 'like' , '%' . $keyword . '%')
                ->orWhere('address' , 'like' , '%' . $keyword . '%');
        }
        return $query;
    }
}
