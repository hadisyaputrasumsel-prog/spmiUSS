<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AmiNilaiTahap extends Model
{
    use HasFactory;

    protected $table = 'ami_nilai_tahap';

    protected $fillable = [
        'ami_id',
        'tahap',
        'skor',
        'catatan',
    ];

    protected $casts = [
        'skor' => 'integer',
    ];

    public function amiFinding(): BelongsTo
    {
        return $this->belongsTo(AmiFinding::class, 'ami_id');
    }
}
