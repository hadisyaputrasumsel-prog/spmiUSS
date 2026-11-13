<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pka extends Model
{
    protected $table = 'pka';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'tanggal_disusun' => 'date',
            'tanggal_diperiksa' => 'date',
        ];
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function standard()
    {
        return $this->belongsTo(Standard::class, 'standar_kode', 'kode');
    }

    public function langkah()
    {
        return $this->hasMany(PkaLangkah::class, 'pka_id')->orderBy('no_urut', 'asc');
    }

    public function penyusun()
    {
        return $this->belongsTo(User::class, 'disusun_oleh');
    }

    public function pemeriksa()
    {
        return $this->belongsTo(User::class, 'diperiksa_oleh');
    }
}
