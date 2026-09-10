<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $tahun
 * @property int $kegiatan_id
 * @property string $status
 * @property string|null $tanggal_pelaksanaan
 * @property string|null $dokumen_bukti
 * @property string|null $catatan
 * @property int $updated_by_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BulanMutuStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BulanMutuStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BulanMutuStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BulanMutuStatus whereCatatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BulanMutuStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BulanMutuStatus whereDokumenBukti($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BulanMutuStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BulanMutuStatus whereKegiatanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BulanMutuStatus whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BulanMutuStatus whereTahun($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BulanMutuStatus whereTanggalPelaksanaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BulanMutuStatus whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BulanMutuStatus whereUpdatedById($value)
 * @mixin \Eloquent
 */
class BulanMutuStatus extends Model
{
    protected $guarded = [];
}
