<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AmiFinding extends Model
{
    protected $guarded = [];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function auditor()
    {
        return $this->belongsTo(User::class, 'auditor_id');
    }

    public function standard()
    {
        return $this->belongsTo(Standard::class, 'standar_kode', 'kode');
    }
}
