<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
  protected $fillable = [
    'code',
    'name',
    'slug',
    'image',
    'gallery',
    'depart',
    'description',
    'from_place_name',
    'to_place_code',
    'to_place_name',
    'transport',
    'number_days',
    'number_persons',
    'price_default',
    'price_children',
    'price_baby',
    'note',
    'is_featured',
    'is_active',
    'category_id',
  ];

  protected $casts = [
    'gallery' => 'array',
    'to_place_code' => 'array'
  ];

  public function category()
  {
    return $this->belongsTo(Category::class)->withDefault();
  }

  public function ratings()
  {
    return $this->hasMany(Rating::class);
  }

  public function orders()
  {
    return $this->hasMany(Order::class);
  }

  public function scopeCountTourByMonth($q, $month)
  {
    $currentYear = date('Y');

    return $q->whereMonth('created_at', $month)
      ->whereYear('created_at', $currentYear)
      ->count();
  }

  public function scopeCountTour($q)
  {
    return $q->count();
  }

  public function scopeFeatured($q)
  {
    return $q->where('is_featured', true);
  }

  public function scopeActive($q)
  {
    return $q->where('is_active', true);
  }

  public function scopeSearch($query, $search)
  {
    if (!$search) {
      return $query;
    }
    return $query->whereRaw('searchtext @@ to_tsquery(\'english\', ?)', [$search])
      ->orderByRaw('ts_rank(searchtext, to_tsquery(\'english\', ?)) DESC', [$search]);
  }

  public function getAvgRatingAttribute()
  {
    return $this->ratings->avg('scores');
  }

  public function getCountRatingAttribute()
  {
    return $this->ratings->count();
  }
}
