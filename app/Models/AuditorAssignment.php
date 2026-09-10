<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $tahun
 * @property int $auditor_id
 * @property int $unit_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $auditor
 * @property-read \App\Models\Unit $unit
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuditorAssignment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuditorAssignment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuditorAssignment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuditorAssignment whereAuditorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuditorAssignment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuditorAssignment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuditorAssignment whereTahun($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuditorAssignment whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuditorAssignment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
