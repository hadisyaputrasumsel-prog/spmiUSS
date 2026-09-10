<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $tahun
 * @property int $is_active
 * @property string|null $bulan_pelaksanaan
 * @property int $updated_by_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BulanMutuConfig newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BulanMutuConfig newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BulanMutuConfig query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BulanMutuConfig whereBulanPelaksanaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BulanMutuConfig whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BulanMutuConfig whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BulanMutuConfig whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BulanMutuConfig whereTahun($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BulanMutuConfig whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BulanMutuConfig whereUpdatedById($value)
 * @mixin \Eloquent
 */
class BulanMutuConfig extends Model
{
    protected $guarded = [];
}
