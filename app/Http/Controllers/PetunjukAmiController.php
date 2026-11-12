<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Database\Seeders\PetunjukAmiSeeder;
use Illuminate\Http\Request;

class PetunjukAmiController extends Controller
{
    public function index()
    {
        $setting = Setting::first();
        if (! $setting) {
            $setting = Setting::create([
                'nama_kampus' => 'Universitas Sumatera Selatan',
                'petunjuk_ami' => PetunjukAmiSeeder::defaultContent(),
            ]);
        } elseif ($setting->petunjuk_ami === null) {
            $setting->update(['petunjuk_ami' => PetunjukAmiSeeder::defaultContent()]);
        }

        return view('petunjuk-ami.index', compact('setting'));
    }

    public function update(Request $request)
    {
        $roleKode = auth()->user()->role->kode ?? '';
        if (! in_array($roleKode, ['admin', 'super_admin', 'lpma'], true)) {
            abort(403, 'Anda tidak memiliki izin untuk mengubah Petunjuk AMI.');
        }

        $setting = Setting::first() ?? Setting::create(['nama_kampus' => 'Universitas Sumatera Selatan']);

        $request->validate([
            'petunjuk_ami' => 'nullable|string',
        ]);

        $setting->update([
            'petunjuk_ami' => $request->petunjuk_ami,
        ]);

        return redirect()->route('petunjuk-ami.index')->with('success', 'Petunjuk Pelaksanaan AMI berhasil diperbarui.');
    }
}
