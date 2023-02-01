<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entresorti extends Model
{
    use HasFactory;
    protected $fillable = [
        'motif',
        'prix',
        'type',
        'user_id'
    ];
}
