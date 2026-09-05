<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::firstOrCreate(
    ['email' => 'kaprodi.ilkom@uss.ac.id'],
    [
        'name' => 'Ketua Prodi Ilmu Komputer',
        'password' => bcrypt('password'),
        'role_id' => 5,
        'unit_id' => 14,
    ]
);

$user->update([
    'name' => 'Ketua Prodi Ilmu Komputer',
    'role_id' => 5,
    'unit_id' => 14,
]);

$manajemenUnit = App\Models\Unit::where('nama', 'like', '%Manajemen%')->where('jenis', 'Program Studi')->first();
if ($manajemenUnit) {
    App\Models\User::where('email', 'kaprodi.manajemen@uss.ac.id')->update(['unit_id' => $manajemenUnit->id]);
}

echo "Berhasil membuat akun Kaprodi Ilmu Komputer.\n";
