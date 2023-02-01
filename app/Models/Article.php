<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;
    protected $fillable = [
        'nom',
        'image',
        'prixAchat',
        'stock',
        'vendue',
        'alert',
        'prixVente'
    ];
}
