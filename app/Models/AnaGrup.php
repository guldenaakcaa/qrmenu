<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnaGrup extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 't_anagrup';
    protected $fillable = [
        'id',
        'anaGrup',
        'siraNo',
        'anaGrupResimPath',
    ];
}

