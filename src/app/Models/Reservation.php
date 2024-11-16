<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','restaurant_id','date','number','status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }



    public function scopeDateSearch($query,$start_time,$end_time,$start_date,$end_date) {

        $startTime = $start_time ? Carbon::parse($start_time)->format('H:i:s') : '00:00:00';
        $endTime = $end_time ? Carbon::parse($end_time)->format('H:i:s') : '23:59:59';

        if(!empty($start_date) && !empty($end_date)) {
            $startDateTime = Carbon::parse("{$start_date} {$startTime}");
            $endDateTime = Carbon::parse("{$end_date} {$endTime}");
            $query->whereBetween('date',[$startDateTime,$endDateTime]);

        } elseif (!empty($start_date)) {
            $startDateTime = Carbon::parse("{$start_date} {$startTime}");
            $query->where('date','>=',$startDateTime);

        } elseif (!empty($end_date)) {
            $endDateTime = Carbon::parse("{$end_date} {$endTime}");
            $query->where('date', '<=', $endDateTime);
        }
        return $query;
    }

    public function scopeIdSearch($query, $res_id)
    {
        if (!empty($res_id)) {
            $query->where('id', $res_id);
        }
        return $query;
    }

    public function scopeNumberSearch($query, $number)
    {
        if (!empty($number)) {
            $query->where('number', $number);
        }
        return $query;
    }

    public function scopeStatusSearch($query, $status)
    {
        if (!empty($status)) {
            $query->where('status', $status);
        }
        return $query;
    }

    public function scopeKeywordSearch($query,$keyword) {
        if(!empty($keyword)) {
            $query->whereHas('user',function($query) use ($keyword){
                $query->where('name', 'like', '%' . $keyword . '%');
            });
        }
        return $query;
    }
}
