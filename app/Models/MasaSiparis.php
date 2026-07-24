<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasaSiparis extends Model
{
    use HasFactory;

    protected $table = 'masa_siparis';
    protected $fillable = ['masa_isim', 'urun_adi', 'adet', 'fiyat', 'siparis_saati'];
}
