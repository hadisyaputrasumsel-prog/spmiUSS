<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$nas = App\Models\NonacademicCategory::all();
foreach($nas as $na) {
    App\Models\Standard::updateOrCreate(
        ['kode' => $na->kode],
        [
            'nama' => $na->nama,
            'kelompok' => 'Non-Akademik',
            'sasaran_unit' => ['Non-Akademik']
        ]
    );
}
echo 'Migrated ' . count($nas) . ' nonacademic categories to standards table.';
