<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $nama
 * @property string $jenis
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $pimpinan
 * @property string|null $nuptk_pimpinan
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AmiFinding> $amiFindings
 * @property-read int|null $ami_findings_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unit query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unit whereJenis($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unit whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unit whereNuptkPimpinan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unit wherePimpinan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unit whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Unit extends Model
{
    protected $guarded = [];

    public function amiFindings()
    {
        return $this->hasMany(AmiFinding::class);
    }
}
