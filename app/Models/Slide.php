<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slide extends Model
{
  protected $fillable = ['title', 'image', 'link', 'is_active'];

  public function scopeActive($q)
  {
    return $q->where('is_active', true);
  }
}
