<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','reservation_id','rating','comment'];


    public function user() {
        $this->belongsTo(User::class);
    }

    public function reservation() {
        $this->belongsTo(Reservation::class);
    }
}
