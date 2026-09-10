<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $akar_penyebab
 * @property string|null $akibat
 * @property string|null $rekomendasi
 * @property string|null $tanggapan_auditi
 * @property string|null $rencana_pencegahan
 * @property-read \App\Models\User $auditor
 * @property-read \App\Models\Standard|null $standard
 * @property-read \App\Models\Unit $unit
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
 * @mixin \Eloquent
 */
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
