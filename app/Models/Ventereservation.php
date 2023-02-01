<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ventereservation extends Model
{
    use HasFactory;
    protected $fillable = [
        'nom',
        'prix',
        'quantite',
        'identifiant',
        'user_id'
    ];
}
