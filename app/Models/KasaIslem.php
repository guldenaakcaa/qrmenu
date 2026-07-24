<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KasaIslem extends Model
{
    use HasFactory;

    protected $fillable = ['tarih', 'islem_saati', 'turu', 'tutar', 'aciklama'];
}
