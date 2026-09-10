<?php

namespace Database\Seeders;

use App\Models\AmiFinding;
use App\Models\BulanMutuConfig;
use App\Models\Standard;
use App\Models\Unit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DummyAmiSeeder extends Seeder
{
    public function run()
    {
        $admin = User::first();
        if (! $admin) {
            return;
        }

        $activeConfig = BulanMutuConfig::where('is_active', true)->first();
        $tahun = $activeConfig ? $activeConfig->tahun : date('Y');

        // We will seed for a few units to prevent overloading
        $units = Unit::where('nama', 'LIKE', '%Ilmu Komputer%')->orWhere('id', 1)->take(3)->get();
        $standards = Standard::all();
        $tahapan = ['P1', 'P2', 'P3', 'P4', 'P5'];

        $kategories = ['Sesuai', 'Observasi (OB)', 'KTS Minor', 'KTS Mayor'];

        foreach ($units as $unit) {
            foreach ($standards as $standard) {
                // Simulate some standards applying and some not
                foreach ($tahapan as $t) {
                    $kat = collect($kategories)->random();
                    AmiFinding::updateOrCreate(
                        [
                            'standar_kode' => $standard->kode,
                            'tahap' => $t,
                            'unit_id' => $unit->id,
                        ],
                        [
                            'jenis' => ($standard->kelompok == 'Non-Akademik') ? 'nonakademik' : 'akademik',
                            'kategori_temuan' => $kat,
                            'skor' => rand(1, 4),
                            'uraian' => 'Dummy temuan evaluasi '.$t,
                            'rencana_tindakan' => $kat != 'Sesuai' ? 'Uraian RTL Dummy' : null,
                            'pic' => $kat != 'Sesuai' ? 'Bpk/Ibu PIC' : null,
                            'batas_waktu' => $kat != 'Sesuai' ? Carbon::now()->addDays(30) : null,
                            'tanggal' => Carbon::createFromDate($tahun, mt_rand(1, 6), mt_rand(1, 28))->format('Y-m-d'),
                            'auditor_id' => $admin->id,
                            'status_tindak_lanjut' => $kat != 'Sesuai' ? 'Proses' : 'Selesai',
                        ]
                    );
                }
            }
        }
    }
}
