<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable implements JWTSubject
{
  use Notifiable;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name',
    'email',
    'password',
    'avatar',
    'phone_number',
    'address',
    'is_active',
    'role_id',
  ];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
    'password',
  ];

  /**
   * The attributes that should be cast to native types.
   *
   * @var array
   */
  protected $casts = [
    'email_verified_at' => 'datetime',
  ];

  public function getJWTIdentifier()
  {
    return $this->getKey();
  }

  public function getJWTCustomClaims()
  {
    return [];
  }

  public function setPasswordAttribute($password)
  {
    $this->attributes['password'] = Hash::make($password);
  }

  public function role()
  {
    return $this->belongsTo(Role::class)->withDefault();
  }

  public function ratings()
  {
    return $this->hasMany(Rating::class);
  }

  public function orders()
  {
    return $this->hasMany(Order::class);
  }

  public function scopeCountUserByMonth($q, $roleId, $month)
  {
    $currentYear = date('Y');

    return $q->where('role_id', $roleId)
      ->whereMonth('created_at', $month)
      ->whereYear('created_at', $currentYear)
      ->count();
  }

  public function scopeCountUser($q, $roleId)
  {
    return $q->where('role_id', $roleId)->count();
  }

  public function scopeHasRole($q, $roleId)
  {
    return $q->where('role_id', $roleId);
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
}
