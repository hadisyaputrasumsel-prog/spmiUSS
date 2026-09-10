<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $nama_kampus
 * @property string|null $alamat_kampus
 * @property string|null $email_kampus
 * @property string|null $telepon_kampus
 * @property string|null $website_kampus
 * @property string|null $logo_kampus
 * @property string|null $nama_rektor
 * @property string|null $nuptk_rektor
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereAlamatKampus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereEmailKampus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereLogoKampus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereNamaKampus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereNamaRektor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereNuptkRektor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereTeleponKampus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereWebsiteKampus($value)
 * @mixin \Eloquent
 */
class Setting extends Model
{
    protected $guarded = [];
}
