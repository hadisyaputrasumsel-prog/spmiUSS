<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

/**
 * @property int $id
 * @property int $tahun
 * @property int $is_active
 * @property string|null $bulan_pelaksanaan
 * @property int $updated_by_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
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
 *
 * @mixin \Eloquent
 */
class BulanMutuConfig extends Model
{
    protected $guarded = [];

    /**
     * Resolve the active SPMI execution year, falling back to the current year.
     */
    public static function activeYear(): int
    {
        try {
            if (Schema::hasTable('bulan_mutu_configs')) {
                $config = Schema::hasColumn('bulan_mutu_configs', 'is_active')
                    ? static::where('is_active', true)->first()
                    : static::first();

                if ($config) {
                    return (int) $config->tahun;
                }
            }
        } catch (\Throwable) {
            // Fall back to current calendar year if schema is not ready.
        }

        return (int) date('Y');
    }

    /**
     * Return distinct cycle years sorted descending, always including active and requested years.
     *
     * @return array<int, int>
     */
    public static function availableYears(?int $includeYear = null): array
    {
        $years = [];

        try {
            if (Schema::hasTable('bulan_mutu_statuses')) {
                $years = array_merge(
                    $years,
                    BulanMutuStatus::select('tahun')->distinct()->pluck('tahun')->map(fn ($y) => (int) $y)->all()
                );
            }

            if (Schema::hasTable('bulan_mutu_configs')) {
                $years = array_merge(
                    $years,
                    static::select('tahun')->distinct()->pluck('tahun')->map(fn ($y) => (int) $y)->all()
                );
            }
        } catch (\Throwable) {
            // Ignore missing tables during early setup.
        }

        $years[] = (int) date('Y');
        $years[] = static::activeYear();

        if ($includeYear !== null) {
            $years[] = $includeYear;
        }

        $years = array_values(array_unique($years));
        rsort($years);

        return $years;
    }
}
