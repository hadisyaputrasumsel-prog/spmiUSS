<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $tahun
 * @property int $unit_id
 * @property int|null $submitted_by_id
 * @property string $submitted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Unit|null $unit
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LedSubmission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LedSubmission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LedSubmission query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LedSubmission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LedSubmission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LedSubmission whereSubmittedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LedSubmission whereSubmittedById($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LedSubmission whereTahun($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LedSubmission whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LedSubmission whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class LedSubmission extends Model
{
    protected $table = 'led_submissions';
    protected $guarded = [];

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'submitted_by_id');
    }
}
