<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LedEntryStage extends Model
{
    protected $guarded = [];
    public $timestamps = false;

    protected $casts = [
        'data_spesifik' => 'array',
    ];
}
