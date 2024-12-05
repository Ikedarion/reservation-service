<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','reservation_id','rating','comment','title','nickname'];


    public function user() {
        $this->belongsTo(User::class);
    }

    public function reservation() {
        $this->belongsTo(Reservation::class);
    }

    public function scopeWithStarRating($query,$starRating)
    {
        if($starRating && $starRating != '') {
            $query->where('rating',$starRating);
        }
        return $query;
    }

    public function scopeSortBy($query, $sortBy)
    {
        if ($sortBy == 'newest') {
            $query->orderBy('created_at', 'desc');
        } elseif ($sortBy == 'rating') {
            $query->orderBy('rating', 'desc');
        }
        return $query;
    }
}
