<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class EmailSubscription extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'email',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];
} 