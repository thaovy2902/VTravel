<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $fillable = ['name', 'email', 'phone_number', 'type', 'content', 'is_seen'];
}
