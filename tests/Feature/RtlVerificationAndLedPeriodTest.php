<?php

namespace Tests\Feature;

use App\Models\AmiFinding;
use App\Models\AuditorAssignment;
use App\Models\BulanMutuConfig;
use App\Models\LedEntry;
use App\Models\LedEntryStage;
use App\Models\Role;
use App\Models\Standard;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class RtlVerificationAndLedPeriodTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_and_assigned_auditor_can_verify_rtl_and_complete_seventh_ppepp_stage(): void
    {
        $superAdminRole = Role::forceCreate(['kode' => 'super_admin', 'nama' => 'Super Admin']);
        $auditorRole = Role::forceCreate(['kode' => 'auditor', 'nama' => 'Auditor']);
        $prodiRole = Role::forceCreate(['kode' => 'auditee_prodi', 'nama' => 'Program Studi']);

        $unit = Unit::forceCreate(['nama' => 'Program Studi Ilmu Komputer', 'jenis' => 'Program Studi']);
        $otherUnit = Unit::forceCreate(['nama' => 'Program Studi Manajemen', 'jenis' => 'Program Studi']);

        $superAdmin = User::factory()->create(['role_id' => $superAdminRole->id]);
        $assignedAuditor = User::factory()->create(['role_id' => $auditorRole->id]);
        $unassignedProdi = User::factory()->create(['role_id' => $prodiRole->id, 'unit_id' => $otherUnit->id]);

        BulanMutuConfig::create([
            'tahun' => 2023,
            'is_active' => true,
            'updated_by_id' => $superAdmin->id,
        ]);

        AuditorAssignment::create([
            'tahun' => '2023',
            'auditor_id' => $assignedAuditor->id,
            'unit_id' => $unit->id,
            'peran' => 'Auditor Ketua',
        ]);

        $findingOne = AmiFinding::forceCreate([
            'jenis' => 'akademik',
            'standar_kode' => 'S-01',
            'tahap' => 'P1',
            'unit_id' => $unit->id,
            'kategori_temuan' => 'Sesuai',
            'auditor_id' => $assignedAuditor->id,
            'tanggal' => '2023-08-01',
            'status_tindak_lanjut' => 'N/A',
        ]);

        $findingTwo = AmiFinding::forceCreate([
            'jenis' => 'akademik',
            'standar_kode' => 'S-02',
            'tahap' => 'P2',
            'unit_id' => $unit->id,
            'kategori_temuan' => 'KTS Mayor',
            'auditor_id' => $assignedAuditor->id,
            'tanggal' => '2023-08-01',
            'status_tindak_lanjut' => 'N/A',
        ]);

        // Updating RTL on a finding with N/A moves it to Sedang Berjalan
        $this->actingAs($superAdmin)->put(route('ami.update-rtl', $findingTwo->id), [
            'rencana_tindakan' => '[SIMULASI] Perbaiki dokumen MBKM',
            'pic' => 'Kaprodi',
            'batas_waktu' => '2023-09-01',
        ])->assertRedirect();

        $this->assertSame('Sedang Berjalan', $findingTwo->fresh()->status_tindak_lanjut);

        // Unassigned auditee cannot verify RTL
        $this->actingAs($unassignedProdi)->put(route('ami.verify-rtl', $findingTwo->id), [
            'status_tindak_lanjut' => 'Selesai',
        ])->assertForbidden();

        // Assigned auditor verifies individual finding
        $this->actingAs($assignedAuditor)->put(route('ami.verify-rtl', $findingTwo->id), [
            'status_tindak_lanjut' => 'Selesai',
            'catatan_tindak_lanjut' => 'Bukti perbaikan telah diverifikasi.',
        ])->assertRedirect();

        $findingTwo->refresh();
        $this->assertSame('Selesai', $findingTwo->status_tindak_lanjut);
        $this->assertSame('Bukti perbaikan telah diverifikasi.', $findingTwo->catatan_tindak_lanjut);

        // Super Admin verifies remaining findings for the unit/year in one click
        $ppeppBefore = $this->actingAs($superAdmin)->get(route('unit.ppepp', ['id' => $unit->id, 'tahun' => 2023]));
        $ppeppBefore->assertOk();
        $ppeppBefore->assertSee('Verifikasi Semua RTL (Tandai Selesai)');
        $ppeppBefore->assertSee('Verifikasi RTL');

        $this->actingAs($superAdmin)->post(route('unit.verify-all-rtl', $unit->id), [
            'tahun' => 2023,
        ])->assertRedirect(route('unit.ppepp', ['id' => $unit->id, 'tahun' => 2023]));

        $this->assertSame('Selesai', $findingOne->fresh()->status_tindak_lanjut);

        $amiPage = $this->actingAs($superAdmin)->get(route('ami.index', ['tahun' => 2023, 'unit_id' => $unit->id]));
        $amiPage->assertOk();
        $amiPage->assertSee('Selesai');
        $amiPage->assertSee('Verifikasi RTL');
    }

    public function test_led_p2_supports_both_free_text_period_and_regular_date_without_server_error(): void
    {
        $this->assertContains(Schema::getColumnType('led_entry_stages', 'tanggal'), ['varchar', 'string']);

        $superAdminRole = Role::forceCreate(['kode' => 'super_admin', 'nama' => 'Super Admin']);
        $user = User::factory()->create(['role_id' => $superAdminRole->id]);
        $unit = Unit::forceCreate(['nama' => 'Program Studi Ilmu Komputer', 'jenis' => 'Program Studi']);

        BulanMutuConfig::create([
            'tahun' => 2023,
            'is_active' => true,
            'updated_by_id' => $user->id,
        ]);

        Standard::create([
            'kode' => 'SM-P2-01',
            'nama' => 'Standar Pelaksanaan Pembelajaran',
            'kelompok' => 'Pendidikan',
            'is_akademik' => true,
            'is_active' => true,
        ]);

        // 1. Free-text period in P2 + standard date in P1
        $this->actingAs($user)->put(route('led.update', 'SM-P2-01'), [
            'tahun' => 2023,
            'unit_id' => $unit->id,
            'stages' => [
                'P1' => [
                    'tanggal' => '2023-08-01',
                    'uraian' => 'Penetapan standar',
                ],
                'P2' => [
                    'tanggal' => 'Semester Ganjil 2026/2027',
                    'uraian' => 'Pelaksanaan semester ganjil',
                ],
            ],
        ])->assertRedirect();

        $entry = LedEntry::where('unit_id', $unit->id)->where('objek_kode', 'SM-P2-01')->firstOrFail();
        $p1 = LedEntryStage::where('led_entry_id', $entry->id)->where('tahap', 'P1')->firstOrFail();
        $p2 = LedEntryStage::where('led_entry_id', $entry->id)->where('tahap', 'P2')->firstOrFail();

        $this->assertSame('2023-08-01', $p1->tanggal);
        $this->assertSame('01/08/2023', $p1->formattedTanggal());
        $this->assertSame('Semester Ganjil 2026/2027', $p2->tanggal);
        $this->assertSame('Semester Ganjil 2026/2027', $p2->formattedTanggal());

        // Ensure AMI create side-by-side LED preview does not 500 on Carbon::parse
        $amiCreate = $this->actingAs($user)->get(route('ami.create', [
            'unit_id' => $unit->id,
            'standar_kode' => 'SM-P2-01',
        ]));
        $amiCreate->assertOk();
        $amiCreate->assertSee('01/08/2023');
        $amiCreate->assertSee('Semester Ganjil 2026/2027');

        // Ensure LED PDF signature block does not force a new page break and PDF route renders cleanly
        $standard = Standard::where('kode', 'SM-P2-01')->firstOrFail();
        $pdfHtml = view('led.pdf', [
            'standard' => $standard,
            'entry' => $entry,
            'stageData' => ['P1' => $p1, 'P2' => $p2],
            'tahun' => 2023,
            'unit' => $unit,
            'sharedDocumentsMap' => collect(),
            'autoAttachedByStage' => [],
        ])->render();
        $this->assertStringNotContainsString('page-break-before: always', $pdfHtml);
        $this->assertStringContainsString('Semester Ganjil 2026/2027', $pdfHtml);

        $this->actingAs($user)->get(route('led.pdf', ['kode' => 'SM-P2-01', 'unit_id' => $unit->id, 'tahun' => 2023]))
            ->assertOk();

        // 2. Normal date in P2 also works cleanly
        $this->actingAs($user)->put(route('led.update', 'SM-P2-01'), [
            'tahun' => 2023,
            'unit_id' => $unit->id,
            'stages' => [
                'P2' => [
                    'tanggal' => '2023-08-15',
                    'uraian' => 'Pelaksanaan dengan tanggal biasa',
                ],
            ],
        ])->assertRedirect();

        $p2->refresh();
        $this->assertSame('2023-08-15', $p2->tanggal);
        $this->assertSame('15/08/2023', $p2->formattedTanggal());
    }
}
