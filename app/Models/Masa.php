<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Masa extends Model
{
    use HasFactory;
    
    protected $fillable = ['isim', 'slug', 'durum', 'guncel_tutar'];
}
