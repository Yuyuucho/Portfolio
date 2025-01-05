<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'roomname',
        'roompass',
        'gamepass',
        'is_active',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('is_winner')->withTimestamps();
    }

    
}
