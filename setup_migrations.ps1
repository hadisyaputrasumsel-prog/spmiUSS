$migrationsPath = "database\migrations"

# Find roles and units migrations
$rolesFile = Get-ChildItem -Path $migrationsPath -Filter "*create_roles_table.php" | Select-Object -ExpandProperty Name
$unitsFile = Get-ChildItem -Path $migrationsPath -Filter "*create_units_table.php" | Select-Object -ExpandProperty Name
$usersFile = Get-ChildItem -Path $migrationsPath -Filter "*create_users_table.php" | Select-Object -ExpandProperty Name

# Rename roles and units to run before users (0000_...)
if ($rolesFile -notmatch "^0000_") {
    Rename-Item -Path "$migrationsPath\$rolesFile" -NewName "0000_00_00_000000_create_roles_table.php"
}
if ($unitsFile -notmatch "^0000_") {
    Rename-Item -Path "$migrationsPath\$unitsFile" -NewName "0000_00_00_000001_create_units_table.php"
}

# Update users migration file using regex
$usersContent = Get-Content -Path "$migrationsPath\$usersFile" -Raw
$usersContent = $usersContent -replace "`$table->string\('email'\)->unique\(\);", "`$table->string('email')->unique();`n            `$table->foreignId('role_id')->constrained('roles');`n            `$table->foreignId('unit_id')->nullable()->constrained('units');"
Set-Content -Path "$migrationsPath\$usersFile" -Value $usersContent

# Function to rewrite migration schema
function Update-Migration {
    param($filter, $schema)
    $file = Get-ChildItem -Path $migrationsPath -Filter $filter | Select-Object -First 1
    if ($file) {
        $content = Get-Content -Path $file.FullName -Raw
        $content = $content -replace '(?s)\$table->id\(\);.*?(?=\$table->timestamps\(\);)', $schema
        Set-Content -Path $file.FullName -Value $content
    }
}

Update-Migration "*create_roles_table*" "`$table->id();`n            `$table->string('kode')->unique();`n            `$table->string('nama');`n            `$table->string('deskripsi')->nullable();`n            "
Update-Migration "*create_units_table*" "`$table->id();`n            `$table->string('nama');`n            `$table->enum('jenis', ['Universitas', 'Fakultas', 'Program Studi', 'Non-Akademik']);`n            "
Update-Migration "*create_standards_table*" "`$table->id();`n            `$table->string('kode')->unique();`n            `$table->string('nama');`n            `$table->string('kelompok');`n            "
Update-Migration "*create_nonacademic_categories_table*" "`$table->id();`n            `$table->string('kode')->unique();`n            `$table->string('nama');`n            "
Update-Migration "*create_bulan_mutu_activities_table*" "`$table->id();`n            `$table->integer('index_kegiatan')->unique();`n            `$table->string('nama');`n            `$table->string('tahap');`n            `$table->string('pic');`n            `$table->string('dokumen')->nullable();`n            "
Update-Migration "*create_bulan_mutu_configs_table*" "`$table->id();`n            `$table->integer('tahun')->unique();`n            `$table->string('bulan_pelaksanaan')->nullable();`n            `$table->foreignId('updated_by_id')->constrained('users');`n            "
Update-Migration "*create_bulan_mutu_unit_statuses_table*" "`$table->id();`n            `$table->integer('tahun');`n            `$table->foreignId('unit_id')->constrained('units');`n            `$table->boolean('aktif')->default(true);`n            `$table->foreignId('updated_by_id')->constrained('users');`n            `$table->unique(['tahun', 'unit_id']);`n            "
Update-Migration "*create_bulan_mutu_statuses_table*" "`$table->id();`n            `$table->integer('tahun');`n            `$table->foreignId('kegiatan_id')->constrained('bulan_mutu_activities');`n            `$table->enum('status', ['Belum Dilaksanakan', 'Terlaksana Sesuai Rencana', 'Terlaksana - Tertunda', 'Dibatalkan'])->default('Belum Dilaksanakan');`n            `$table->date('tanggal_pelaksanaan')->nullable();`n            `$table->text('catatan')->nullable();`n            `$table->foreignId('updated_by_id')->constrained('users');`n            `$table->unique(['tahun', 'kegiatan_id']);`n            "
Update-Migration "*create_led_entries_table*" "`$table->id();`n            `$table->integer('tahun');`n            `$table->enum('jenis', ['akademik', 'nonakademik']);`n            `$table->string('objek_kode');`n            `$table->foreignId('unit_id')->constrained('units');`n            `$table->foreignId('diisi_oleh_id')->constrained('users');`n            `$table->unique(['tahun', 'jenis', 'objek_kode', 'unit_id']);`n            "
Update-Migration "*create_led_entry_stages_table*" "`$table->id();`n            `$table->foreignId('led_entry_id')->constrained('led_entries')->cascadeOnDelete();`n            `$table->enum('tahap', ['P1', 'P2', 'P3', 'P4', 'P5']);`n            `$table->date('tanggal')->nullable();`n            `$table->string('penanggung_jawab')->nullable();`n            `$table->text('uraian')->nullable();`n            `$table->text('catatan')->nullable();`n            `$table->json('bukti')->nullable();`n            `$table->unique(['led_entry_id', 'tahap']);`n            "
Update-Migration "*create_ami_findings_table*" "`$table->id();`n            `$table->enum('jenis', ['akademik', 'nonakademik']);`n            `$table->string('standar_kode');`n            `$table->enum('tahap', ['P1', 'P2', 'P3', 'P4', 'P5']);`n            `$table->foreignId('unit_id')->constrained('units');`n            `$table->enum('kategori_temuan', ['Sesuai', 'Observasi (OB)', 'KTS Minor', 'KTS Mayor']);`n            `$table->text('uraian')->nullable();`n            `$table->text('rencana_tindakan')->nullable();`n            `$table->string('pic')->nullable();`n            `$table->date('batas_waktu')->nullable();`n            `$table->foreignId('auditor_id')->constrained('users');`n            `$table->date('tanggal');`n            `$table->enum('status_tindak_lanjut', ['Belum', 'Proses', 'Selesai', 'N/A'])->default('N/A');`n            `$table->text('catatan_tindak_lanjut')->nullable();`n            "
