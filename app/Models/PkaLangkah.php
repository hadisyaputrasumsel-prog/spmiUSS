<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PkaLangkah extends Model
{
    protected $table = 'pka_langkah';

    protected $guarded = [];

    public function pka()
    {
        return $this->belongsTo(Pka::class, 'pka_id');
    }
}
