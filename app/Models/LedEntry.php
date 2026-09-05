<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LedEntry extends Model
{
    protected $guarded = [];

    public function stages()
    {
        return $this->hasMany(LedEntryStage::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
}
