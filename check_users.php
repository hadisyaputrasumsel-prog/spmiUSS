<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::where('email', 'kaprodi.manajemen@uss.ac.id')->first();
echo "Manajemen Kaprodi:\n";
if ($user) {
    print_r($user->toArray());
} else {
    echo "Not found\n";
}

$units = App\Models\Unit::where('nama', 'like', '%Ilmu Komputer%')->orWhere('nama', 'like', '%Ilkom%')->get();
echo "\nUnits:\n";
print_r($units->toArray());
