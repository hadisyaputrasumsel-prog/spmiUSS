<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $led_entry_id
 * @property string $tahap
 * @property string|null $tanggal
 * @property string|null $penanggung_jawab
 * @property string|null $uraian
 * @property string|null $catatan
 * @property string|null $bukti
 * @property array<array-key, mixed>|null $data_spesifik
 * @property string|null $created_at
 * @property string|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LedEntryStage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LedEntryStage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LedEntryStage query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LedEntryStage whereBukti($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LedEntryStage whereCatatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LedEntryStage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LedEntryStage whereDataSpesifik($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LedEntryStage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LedEntryStage whereLedEntryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LedEntryStage wherePenanggungJawab($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LedEntryStage whereTahap($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LedEntryStage whereTanggal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LedEntryStage whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LedEntryStage whereUraian($value)
 * @mixin \Eloquent
 */
class LedEntryStage extends Model
{
    protected $guarded = [];
    public $timestamps = false;

    protected $casts = [
        'data_spesifik' => 'array',
    ];
}
