<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ayar extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 't_ayar';

    protected $fillable = [
        'logo',
        'url',
        'baslik',
        'telefon',
        'localeLang',
        'slogan',
        'favicon',
        'adres',
        'google_map_url',
        'instagram_url',
        'whatsapp_number',
        'wifi_ssid',
        'wifi_password',
        'karsilama_gorsel',
        'para_birimi',
        'kdv_orani',
        'menu_durumu',
        'coklu_dil_aktif'
    ];
}
