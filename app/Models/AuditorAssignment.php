<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditorAssignment extends Model
{
    protected $guarded = [];

    public function auditor()
    {
        return $this->belongsTo(User::class, 'auditor_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
