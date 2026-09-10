<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $tahun
 * @property string $jenis
 * @property string $objek_kode
 * @property int $unit_id
 * @property int $diisi_oleh_id
 * @property string $status_pengisian
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LedEntryStage> $stages
 * @property-read int|null $stages_count
 * @property-read \App\Models\Unit $unit
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LedEntry newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LedEntry newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LedEntry query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LedEntry whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LedEntry whereDiisiOlehId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LedEntry whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LedEntry whereJenis($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LedEntry whereObjekKode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LedEntry whereStatusPengisian($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LedEntry whereTahun($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LedEntry whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LedEntry whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
