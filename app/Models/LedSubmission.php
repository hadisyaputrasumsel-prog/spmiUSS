<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LedSubmission extends Model
{
    protected $table = 'led_submissions';
    protected $guarded = [];

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'submitted_by_id');
    }
}
