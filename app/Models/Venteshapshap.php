<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venteshapshap extends Model
{
    use HasFactory;
    protected $fillable = [
        'type',
        'prix',
        'user_id'
    ];
}
