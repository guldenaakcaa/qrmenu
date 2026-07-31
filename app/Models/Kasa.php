<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kasa extends Model
{
    use HasFactory;
    
    protected $fillable = ['tarih', 'nakit_toplam', 'kredi_karti_toplam', 'yemek_karti_toplam', 'veresiye_toplam', 'genel_toplam'];
}
