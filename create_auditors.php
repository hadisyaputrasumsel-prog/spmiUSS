<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Unit;
use App\Models\AuditorAssignment;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

$roleAuditor = Role::where('kode', 'auditor')->first();
if (!$roleAuditor) {
    echo "Auditor role not found!";
    exit;
}

$unit = Unit::where('nama', 'like', '%Ilmu Komputer%')->first();
if (!$unit) {
    echo "Unit Ilmu Komputer not found!";
    exit;
}

// Create Auditor 1
$auditor1 = User::updateOrCreate(
    ['email' => 'auditor1@uss.ac.id'],
    [
        'name' => 'Auditor Satu',
        'password' => Hash::make('password'),
        'role_id' => $roleAuditor->id,
    ]
);

// Create Auditor 2
$auditor2 = User::updateOrCreate(
    ['email' => 'auditor2@uss.ac.id'],
    [
        'name' => 'Auditor Dua',
        'password' => Hash::make('password'),
        'role_id' => $roleAuditor->id,
    ]
);

// Assign both to Ilmu Komputer for years 2024, 2025, 2026
$years = [2024, 2025, 2026];
foreach ($years as $tahun) {
    AuditorAssignment::updateOrCreate([
        'tahun' => $tahun,
        'auditor_id' => $auditor1->id,
        'unit_id' => $unit->id,
    ]);
    
    AuditorAssignment::updateOrCreate([
        'tahun' => $tahun,
        'auditor_id' => $auditor2->id,
        'unit_id' => $unit->id,
    ]);
}

echo "Berhasil membuat 2 akun auditor (auditor1@uss.ac.id & auditor2@uss.ac.id) dan menugaskannya ke Prodi Ilmu Komputer untuk tahun 2024, 2025, dan 2026.\n";
