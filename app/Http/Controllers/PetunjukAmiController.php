<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class PetunjukAmiController extends Controller
{
    public function index()
    {
        $setting = Setting::first();
        if (!$setting) {
            $setting = Setting::create(['nama_kampus' => 'Universitas Sumatera Selatan']);
        }
        
        return view('petunjuk-ami.index', compact('setting'));
    }

    public function update(Request $request)
    {
        $setting = Setting::first();
        
        $request->validate([
            'petunjuk_ami' => 'nullable|string',
        ]);

        if ($setting) {
            $setting->update([
                'petunjuk_ami' => $request->petunjuk_ami
            ]);
        }

        return redirect()->route('petunjuk-ami.index')->with('success', 'Petunjuk Pelaksanaan AMI berhasil diperbarui.');
    }
}
