<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

/**
 * @property int $id
 * @property string $jenis
 * @property string $standar_kode
 * @property int|null $skor
 * @property string $tahap
 * @property int $unit_id
 * @property string $kategori_temuan
 * @property string|null $uraian
 * @property string|null $rencana_tindakan
 * @property string|null $pic
 * @property string|null $batas_waktu
 * @property int $auditor_id
 * @property string $tanggal
 * @property string $status_tindak_lanjut
 * @property string|null $catatan_tindak_lanjut
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $akar_penyebab
 * @property string|null $akibat
 * @property string|null $rekomendasi
 * @property string|null $tanggapan_auditi
 * @property string|null $rencana_pencegahan
 * @property-read User $auditor
 * @property-read Standard|null $standard
 * @property-read Unit $unit
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AmiFinding newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AmiFinding newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AmiFinding query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AmiFinding whereAkarPenyebab($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AmiFinding whereAkibat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AmiFinding whereAuditorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AmiFinding whereBatasWaktu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AmiFinding whereCatatanTindakLanjut($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AmiFinding whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AmiFinding whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AmiFinding whereJenis($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AmiFinding whereKategoriTemuan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AmiFinding wherePic($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AmiFinding whereRekomendasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AmiFinding whereRencanaPencegahan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AmiFinding whereRencanaTindakan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AmiFinding whereSkor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AmiFinding whereStandarKode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AmiFinding whereStatusTindakLanjut($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AmiFinding whereTahap($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AmiFinding whereTanggal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AmiFinding whereTanggapanAuditi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AmiFinding whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AmiFinding whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AmiFinding whereUraian($value)
 *
 * @mixin \Eloquent
 */
class AmiFinding extends Model
{
    protected $guarded = [];

    protected static ?bool $hasTahunColumn = null;

    public static function hasTahunColumn(): bool
    {
        if (static::$hasTahunColumn === null) {
            try {
                static::$hasTahunColumn = Schema::hasColumn('ami_findings', 'tahun');
            } catch (\Throwable $e) {
                static::$hasTahunColumn = false;
            }
        }

        return static::$hasTahunColumn;
    }

    public function scopeForTahun($query, $tahun)
    {
        if (static::hasTahunColumn()) {
            return $query->where(function ($q) use ($tahun) {
                $q->where('tahun', $tahun)
                    ->orWhereYear('tanggal', $tahun);
            });
        }

        return $query->whereYear('tanggal', $tahun);
    }

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

    public function pka()
    {
        return $this->hasOne(Pka::class, 'standar_kode', 'standar_kode')
            ->where('unit_id', $this->unit_id);
    }

    public function nilaiTahap()
    {
        return $this->hasMany(AmiNilaiTahap::class, 'ami_id');
    }

    public function getSkorTahap(string $tahap): ?int
    {
        $nt = $this->nilaiTahap->firstWhere('tahap', $tahap);
        if ($nt && $nt->skor !== null) {
            return (int) $nt->skor;
        }

        return $this->skor !== null ? (int) $this->skor : null;
    }
}
