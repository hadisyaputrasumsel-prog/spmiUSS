<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditSesi extends Model
{
    protected $table = 'audit_sesi';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'distribusi' => 'array',
            'tanggal_audit' => 'date',
        ];
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
