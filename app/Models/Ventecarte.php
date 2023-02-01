<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ventecarte extends Model
{
    use HasFactory;
    protected $fillable = [
        'type',
        'prix',
        'quantite',
        'user_id'
    ];
}
