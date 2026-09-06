<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::first();
        if (!$setting) {
            $setting = Setting::create(['nama_kampus' => 'Universitas Sumatera Selatan']);
        }
        
        return view('setting.index', compact('setting'));
    }

    public function update(Request $request)
    {
        $setting = Setting::first();
        
        $request->validate([
            'nama_kampus' => 'required|string|max:255',
            'alamat_kampus' => 'nullable|string',
            'email_kampus' => 'nullable|email|max:255',
            'telepon_kampus' => 'nullable|string|max:50',
            'website_kampus' => 'nullable|string|max:255',
            'nama_rektor' => 'nullable|string|max:255',
            'nuptk_rektor' => 'nullable|string|max:100',
            'logo_kampus' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);

        $data = $request->except(['_token', '_method', 'logo_kampus']);

        if ($request->hasFile('logo_kampus')) {
            // Delete old logo if exists
            if ($setting->logo_kampus && Storage::disk('public')->exists($setting->logo_kampus)) {
                Storage::disk('public')->delete($setting->logo_kampus);
            }
            
            $file = $request->file('logo_kampus');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('logos', $filename, 'public');
            $data['logo_kampus'] = $path;
        }

        $setting->update($data);

        return redirect()->route('setting.index')->with('success', 'Informasi kampus berhasil diperbarui.');
    }
}
