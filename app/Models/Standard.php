<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $kode
 * @property string $nama
 * @property string|null $indikator
 * @property string|null $indikator_p1
 * @property string|null $indikator_p2
 * @property string|null $indikator_p3
 * @property string|null $indikator_p4
 * @property string|null $indikator_p5
 * @property string|null $target
 * @property string|null $acuan
 * @property string|null $template_dokumen
 * @property string|null $penanggung_jawab
 * @property int $is_akademik
 * @property string $kelompok
 * @property array<array-key, mixed>|null $sasaran_unit
 * @property array<array-key, mixed>|null $rubrik_penilaian
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Standard newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Standard newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Standard query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Standard whereAcuan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Standard whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Standard whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Standard whereIndikator($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Standard whereIndikatorP1($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Standard whereIndikatorP2($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Standard whereIndikatorP3($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Standard whereIndikatorP4($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Standard whereIndikatorP5($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Standard whereIsAkademik($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Standard whereKelompok($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Standard whereKode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Standard whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Standard wherePenanggungJawab($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Standard whereRubrikPenilaian($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Standard whereSasaranUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Standard whereTarget($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Standard whereTemplateDokumen($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Standard whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Standard extends Model
{
    protected $guarded = [];

    protected $casts = [
        'sasaran_unit' => 'array',
        'rubrik_penilaian' => 'array',
    ];

    public function getActiveLedEntry($unit_id)
    {
        return $this->ledEntries()->where('unit_id', $unit_id)
            ->orderBy('tahun', 'desc')
            ->first();
    }

    public function scopeActivePeriod($query)
    {
        $activePeriod = \App\Models\SpmiPeriod::where('is_active', true)->first();
        $activePeriodName = $activePeriod ? $activePeriod->nama : 'Periode Utama';
        
        return $query->where(function($q) use ($activePeriodName) {
            if ($activePeriodName === 'Periode Utama') {
                $q->whereNull('periode')->orWhere('periode', '')->orWhere('periode', 'Periode Utama');
            } else {
                $q->where('periode', $activePeriodName);
            }
        });
    }
}
