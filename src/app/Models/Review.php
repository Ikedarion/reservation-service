<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','reservation_id','rating','comment','title','nickname','reply'];


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

    public function scopeStatus($query, $status)
    {
        if ($status === 'replied') {
            $query->whereNotNull('reply');
        } elseif ($status === 'unReplied') {
            $query->whereNull('reply');
        }
        return $query;
    }

    public function scopeKeywordSearch($query, $keyword)
    {
        if ($keyword) {
            $query->where('title', 'Like', '%' . $keyword . '%')
                ->orWhere('comment', 'Like' , '%' . $keyword . '%')
                ->orWhere('reply', 'Like', '%' . $keyword . '%')
                ->orWhere('nickname', 'Like', '%' . $keyword . '%');
        }
        return $query;
    }

    public function scopeDateSearch($query, $start_date, $end_date)
    {
        if (!empty($start_date) && !empty($end_date)) {
            $query->whereBetween('created_at', [$start_date, $end_date]);
        } elseif (!empty($start_date)){
            $query->where('created_at', '>=', $start_date);
        } elseif (!empty($end_date)) {
            $query->where('created_at', '<=', $end_date);
        }
        return $query;
    }
}
