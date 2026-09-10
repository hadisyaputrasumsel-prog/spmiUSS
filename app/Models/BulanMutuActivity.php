<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $index_kegiatan
 * @property string $nama
 * @property string $tahap
 * @property string $pic
 * @property string|null $dokumen
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BulanMutuActivity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BulanMutuActivity newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BulanMutuActivity query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BulanMutuActivity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BulanMutuActivity whereDokumen($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BulanMutuActivity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BulanMutuActivity whereIndexKegiatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BulanMutuActivity whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BulanMutuActivity wherePic($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BulanMutuActivity whereTahap($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BulanMutuActivity whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class BulanMutuActivity extends Model
{
    //
}
