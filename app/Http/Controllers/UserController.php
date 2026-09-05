<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Unit;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['role', 'unit'])->orderBy('name', 'asc')->get();
        $roles = Role::all();
        $units = Unit::all();
        
        return view('user.index', compact('users', 'roles', 'units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role_id' => 'required|exists:roles,id',
            'unit_id' => 'nullable|exists:units,id',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'unit_id' => $request->unit_id,
        ]);

        return redirect()->route('akun.index')->with('success', 'Akun pengguna baru berhasil ditambahkan.');
    }

    public function destroy(User $akun)
    {
        if ($akun->id === auth()->id()) {
            return redirect()->route('akun.index')->withErrors(['error' => 'Anda tidak bisa menghapus akun Anda sendiri.']);
        }
        
        $akun->delete();
        return redirect()->route('akun.index')->with('success', 'Akun berhasil dihapus.');
    }
}
