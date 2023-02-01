<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;
    protected $fillable = [
        'type',
        'nom',
        'prixAchat',
        'prixVente',
        'quantite',
        'identifiant',
        'user_id'
    ];
}
