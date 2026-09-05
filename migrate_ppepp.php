<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$standards = App\Models\Standard::whereNotNull('indikator')->whereNull('indikator_p1')->get();
$count = 0;
foreach ($standards as $s) {
    $text = $s->indikator;
    preg_match('/\*\*P1\*\*(?:[:\s]*)(.*?)(?=\*\*P2\*\*|$)/is', $text, $m1);
    preg_match('/\*\*P2\*\*(?:[:\s]*)(.*?)(?=\*\*P3\*\*|$)/is', $text, $m2);
    preg_match('/\*\*P3\*\*(?:[:\s]*)(.*?)(?=\*\*P4\*\*|$)/is', $text, $m3);
    preg_match('/\*\*P4\*\*(?:[:\s]*)(.*?)(?=\*\*P5\*\*|$)/is', $text, $m4);
    preg_match('/\*\*P5\*\*(?:[:\s]*)(.*?)$/is', $text, $m5);

    $updated = false;
    if (!empty($m1)) { $s->indikator_p1 = trim($m1[1]); $updated = true; }
    if (!empty($m2)) { $s->indikator_p2 = trim($m2[1]); $updated = true; }
    if (!empty($m3)) { $s->indikator_p3 = trim($m3[1]); $updated = true; }
    if (!empty($m4)) { $s->indikator_p4 = trim($m4[1]); $updated = true; }
    if (!empty($m5)) { $s->indikator_p5 = trim($m5[1]); $updated = true; }
    
    if($updated){
        $s->save();
        $count++;
    }
}
echo "Migrated $count standards.\n";
