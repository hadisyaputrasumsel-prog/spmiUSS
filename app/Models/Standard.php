<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Standard extends Model
{
    protected $guarded = [];

    protected $casts = [
        'sasaran_unit' => 'array',
        'rubrik_penilaian' => 'array',
    ];
}
