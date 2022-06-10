<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $fillable = ['scores', 'content', 'is_active', 'tour_id', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    public function tour()
    {
        return $this->belongsTo(Tour::class)->withDefault();
    }

    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }
}
