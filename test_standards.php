<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$standards = App\Models\Standard::all();
foreach ($standards as $s) {
    echo "=================================\n";
    echo "KODE : " . $s->kode . "\n";
    echo "NAMA : " . $s->nama . "\n";
    echo "INDIKATOR : " . $s->indikator . "\n";
    echo "TARGET : " . $s->target . "\n";
    echo "ACUAN : " . $s->acuan . "\n";
}
echo "=================================\n";
