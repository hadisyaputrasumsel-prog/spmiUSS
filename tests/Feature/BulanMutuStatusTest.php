<?php

namespace Tests\Feature;

use App\Models\BulanMutuActivity;
use App\Models\BulanMutuStatus;
use App\Models\LedEntry;
use App\Models\LedEntryStage;
use App\Models\Role;
use App\Models\Standard;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BulanMutuStatusTest extends TestCase
{
    use RefreshDatabase;

    private function createLpmaAndActivity(): array
    {
        $role = Role::forceCreate(['kode' => 'lpma', 'nama' => 'LPMA']);
        $user = User::factory()->create(['role_id' => $role->id]);
        $activity = BulanMutuActivity::forceCreate([
            'index_kegiatan' => 1,
            'nama' => 'Rapat Persiapan Bulan Mutu',
            'tahap' => 'P1',
            'pic' => 'LPMA',
        ]);

        return [$user, $activity];
    }

    public function test_updating_status_to_terlaksana_without_evidence_shows_error_message_and_does_not_save(): void
    {
        [$user, $activity] = $this->createLpmaAndActivity();

        $response = $this->actingAs($user)->put(route('bulan-mutu.status', $activity->id), [
            'tahun' => 2023,
            'status' => 'Terlaksana Sesuai Rencana',
            'tanggal_pelaksanaan' => '2023-08-07',
        ]);

        $response->assertRedirect(route('bulan-mutu.index', ['tahun' => 2023]));
        $response->assertSessionHas('error');

        $this->assertDatabaseMissing('bulan_mutu_statuses', [
            'tahun' => 2023,
            'kegiatan_id' => $activity->id,
            'status' => 'Terlaksana Sesuai Rencana',
        ]);

        $page = $this->actingAs($user)
            ->withSession(['error' => session('error')])
            ->get(route('bulan-mutu.index', ['tahun' => 2023]));

        $page->assertOk();
        $page->assertSee('Pembaruan gagal!');
        $page->assertDontSee('Upload Evidence / Dokumen Bukti (Opsional)');
    }

    public function test_updating_status_to_terlaksana_with_evidence_saves_date_and_stores_file_on_public_disk(): void
    {
        Storage::fake('public');
        [$user, $activity] = $this->createLpmaAndActivity();

        $response = $this->actingAs($user)->put(route('bulan-mutu.status', $activity->id), [
            'tahun' => 2023,
            'status' => 'Terlaksana Sesuai Rencana',
            'tanggal_pelaksanaan' => '2023-08-07',
            'dokumen_bukti' => UploadedFile::fake()->create('bukti_kegiatan_1.pdf', 100, 'application/pdf'),
            'catatan' => 'Terlaksana di ruang rapat',
        ]);

        $response->assertRedirect(route('bulan-mutu.index', ['tahun' => 2023]));
        $response->assertSessionHas('success');

        $saved = BulanMutuStatus::where('tahun', 2023)
            ->where('kegiatan_id', $activity->id)
            ->firstOrFail();

        $this->assertSame('Terlaksana Sesuai Rencana', $saved->status);
        $this->assertSame('2023-08-07', $saved->tanggal_pelaksanaan);
        $this->assertSame('Terlaksana di ruang rapat', $saved->catatan);
        $this->assertNotNull($saved->dokumen_bukti);
        $this->assertStringStartsWith('storage/bulan_mutu_dokumen/', $saved->dokumen_bukti);
        $this->assertTrue(Storage::disk('public')->exists(str_replace('storage/', '', $saved->dokumen_bukti)));

        $page2023 = $this->actingAs($user)->get(route('bulan-mutu.index', ['tahun' => 2023]));
        $page2023->assertOk();
        $page2023->assertSee('2023-08-07');
        $page2023->assertSee('Bukti Dokumen');
        $page2023->assertSee('data-tanggal="2023-08-07"', false);
        $page2023->assertSee('data-has-doc="1"', false);

        $page2026 = $this->actingAs($user)->get(route('bulan-mutu.index', ['tahun' => 2026]));
        $page2026->assertOk();
        $page2026->assertDontSee('2023-08-07');
    }

    public function test_updating_date_when_evidence_already_exists_succeeds_without_reuploading(): void
    {
        [$user, $activity] = $this->createLpmaAndActivity();

        BulanMutuStatus::create([
            'tahun' => 2023,
            'kegiatan_id' => $activity->id,
            'status' => 'Terlaksana Sesuai Rencana',
            'tanggal_pelaksanaan' => '2023-08-07',
            'dokumen_bukti' => 'storage/bulan_mutu_dokumen/existing.pdf',
            'updated_by_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->put(route('bulan-mutu.status', $activity->id), [
            'tahun' => 2023,
            'status' => 'Terlaksana Sesuai Rencana',
            'tanggal_pelaksanaan' => '2023-08-08',
            'catatan' => 'Revisi tanggal',
        ]);

        $response->assertRedirect(route('bulan-mutu.index', ['tahun' => 2023]));
        $response->assertSessionHas('success');

        $saved = BulanMutuStatus::where('tahun', 2023)
            ->where('kegiatan_id', $activity->id)
            ->firstOrFail();

        $this->assertSame('2023-08-08', $saved->tanggal_pelaksanaan);
        $this->assertSame('storage/bulan_mutu_dokumen/existing.pdf', $saved->dokumen_bukti);
    }

    public function test_led_direct_upload_stores_on_public_disk_and_storage_fallback_serves_legacy_files(): void
    {
        Storage::fake('public');
        Storage::fake('local');

        $role = Role::forceCreate(['kode' => 'super_admin', 'nama' => 'Super Admin']);
        $user = User::factory()->create(['role_id' => $role->id]);
        $unit = Unit::forceCreate(['nama' => 'Program Studi Ilmu Komputer', 'jenis' => 'Program Studi']);
        Standard::create([
            'kode' => 'SM-UPLOAD-01',
            'nama' => 'Standar Uji Upload',
            'kelompok' => 'Pendidikan',
            'is_akademik' => true,
            'is_active' => true,
        ]);

        $this->actingAs($user)->put(route('led.update', 'SM-UPLOAD-01'), [
            'tahun' => 2023,
            'unit_id' => $unit->id,
            'stages' => [
                'P1' => [
                    'tanggal' => '2023-08-01',
                    'uraian' => 'Penetapan dengan file',
                    'dokumen' => [
                        UploadedFile::fake()->create('sk_penetapan.pdf', 50, 'application/pdf'),
                    ],
                ],
            ],
        ])->assertRedirect();

        $entry = LedEntry::where('unit_id', $unit->id)->where('objek_kode', 'SM-UPLOAD-01')->firstOrFail();
        $stage = LedEntryStage::where('led_entry_id', $entry->id)->where('tahap', 'P1')->firstOrFail();
        $files = $stage->decodedBukti()['files'];

        $this->assertCount(1, $files);
        $this->assertStringStartsWith('storage/led_documents/', $files[0]);
        $relativePublicPath = str_replace('storage/', '', $files[0]);
        $this->assertTrue(Storage::disk('public')->exists($relativePublicPath));
        $this->assertFalse(Storage::disk('local')->exists('public/'.$relativePublicPath));

        $this->get('/'.$files[0])->assertOk();

        // Legacy Bulan Mutu file stored in local/public/... is still served via fallback
        Storage::disk('local')->put('public/bulan_mutu_dokumen/1794456430_SIMULASI_BM2023_03.pdf', 'dummy');
        $this->get('/storage/bulan_mutu_dokumen/1794456430_SIMULASI_BM2023_03.pdf')->assertOk();

        // Private Bank Dokumen files on local disk must NOT be exposed via /storage/
        Storage::disk('local')->put('evidence_documents/secret.pdf', 'secret');
        $this->get('/storage/evidence_documents/secret.pdf')->assertNotFound();
    }
}
