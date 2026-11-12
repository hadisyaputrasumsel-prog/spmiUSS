<?php

namespace Tests\Feature;

use App\Models\AuditorAssignment;
use App\Models\EvidenceDocument;
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

class EvidenceDocumentTest extends TestCase
{
    use RefreshDatabase;

    public function test_unit_private_document_cannot_be_accessed_or_attached_by_another_unit(): void
    {
        Storage::fake('local');

        $prodiRole = Role::create(['kode' => 'auditee_prodi', 'nama' => 'Program Studi']);
        $lpmaRole = Role::create(['kode' => 'lpma', 'nama' => 'LPMA']);
        $auditorRole = Role::create(['kode' => 'auditor', 'nama' => 'Auditor']);

        $unitA = Unit::create(['nama' => 'Prodi Ilkom', 'jenis' => 'Program Studi']);
        $unitB = Unit::create(['nama' => 'Prodi Manajemen', 'jenis' => 'Program Studi']);

        $userA = User::factory()->create(['role_id' => $prodiRole->id, 'unit_id' => $unitA->id]);
        $userB = User::factory()->create(['role_id' => $prodiRole->id, 'unit_id' => $unitB->id]);
        $lpma = User::factory()->create(['role_id' => $lpmaRole->id, 'unit_id' => null]);
        $assignedAuditor = User::factory()->create(['role_id' => $auditorRole->id, 'unit_id' => null]);
        $otherAuditor = User::factory()->create(['role_id' => $auditorRole->id, 'unit_id' => null]);

        AuditorAssignment::create([
            'tahun' => '2026',
            'auditor_id' => $assignedAuditor->id,
            'unit_id' => $unitA->id,
            'peran' => 'Auditor Ketua',
        ]);

        $globalDoc = EvidenceDocument::create([
            'judul' => 'Kebijakan Mutu Universitas',
            'kategori' => 'Kebijakan Mutu',
            'scope' => 'global',
            'unit_id' => null,
            'auto_attach' => true,
            'external_url' => 'https://example.com/kebijakan-mutu.pdf',
            'uploaded_by_id' => $lpma->id,
        ]);

        $unitADoc = EvidenceDocument::create([
            'judul' => 'SOP Perwalian Prodi Ilkom',
            'kategori' => 'SOP',
            'scope' => 'unit',
            'unit_id' => $unitA->id,
            'file_path' => 'evidence_documents/sop_ilkom.pdf',
            'original_filename' => 'sop_ilkom.pdf',
            'uploaded_by_id' => $userA->id,
        ]);
        Storage::disk('local')->put('evidence_documents/sop_ilkom.pdf', 'dummy pdf content');

        $this->actingAs($userA)->get(route('evidence-documents.download', $unitADoc))->assertOk();
        $this->actingAs($assignedAuditor)->get(route('evidence-documents.download', $unitADoc))->assertOk();
        $this->actingAs($userB)->get(route('evidence-documents.download', $unitADoc))->assertForbidden();
        $this->actingAs($otherAuditor)->get(route('evidence-documents.download', $unitADoc))->assertForbidden();
        $this->actingAs($userB)->get(route('evidence-documents.download', $globalDoc))->assertRedirect('https://example.com/kebijakan-mutu.pdf');

        Standard::create([
            'kode' => 'SM-TEST-01',
            'nama' => 'Standar Uji',
            'kelompok' => 'Pendidikan',
            'is_akademik' => true,
            'is_active' => true,
        ]);

        // Unit B attempts to attach Unit A's private document ID along with Global doc ID.
        $this->actingAs($userB)->put(route('led.update', 'SM-TEST-01'), [
            'tahun' => 2026,
            'stages' => [
                'P1' => [
                    'uraian' => 'Penetapan unit B',
                    'shared_document_ids' => [$globalDoc->id, $unitADoc->id],
                ],
            ],
        ])->assertRedirect();

        $entryB = LedEntry::where('unit_id', $unitB->id)->where('objek_kode', 'SM-TEST-01')->firstOrFail();
        $stageB = LedEntryStage::where('led_entry_id', $entryB->id)->where('tahap', 'P1')->firstOrFail();
        $buktiB = $stageB->decodedBukti();

        $this->assertSame([$globalDoc->id], $buktiB['shared_document_ids']);
    }

    public function test_auditee_cannot_create_global_document_and_can_promote_upload_to_unit_library(): void
    {
        Storage::fake('local');

        $prodiRole = Role::create(['kode' => 'auditee_prodi', 'nama' => 'Program Studi']);
        $unitA = Unit::create(['nama' => 'Prodi Ilkom', 'jenis' => 'Program Studi']);
        $userA = User::factory()->create(['role_id' => $prodiRole->id, 'unit_id' => $unitA->id]);

        $this->actingAs($userA)->post(route('evidence-documents.store'), [
            'judul' => 'SOP Internal',
            'kategori' => 'SOP',
            'scope' => 'global',
            'external_url' => 'https://example.com/sop.pdf',
        ])->assertRedirect();

        $created = EvidenceDocument::firstOrFail();
        $this->assertSame('unit', $created->scope);
        $this->assertSame($unitA->id, $created->unit_id);

        Standard::create([
            'kode' => 'SM-TEST-02',
            'nama' => 'Standar Uji 2',
            'kelompok' => 'Pendidikan',
            'is_akademik' => true,
            'is_active' => true,
        ]);

        $this->actingAs($userA)->put(route('led.update', 'SM-TEST-02'), [
            'tahun' => 2026,
            'stages' => [
                'P2' => [
                    'uraian' => 'Pelaksanaan',
                    'save_to_unit_library' => '1',
                    'library_title' => 'SOP Praktikum Ilkom',
                    'library_category' => 'SOP',
                    'dokumen' => [UploadedFile::fake()->create('sop_praktikum.pdf', 100, 'application/pdf')],
                ],
            ],
        ])->assertRedirect();

        $promoted = EvidenceDocument::where('judul', 'SOP Praktikum Ilkom')->firstOrFail();
        $this->assertSame('unit', $promoted->scope);
        $this->assertSame($unitA->id, $promoted->unit_id);
        Storage::disk('local')->assertExists($promoted->file_path);

        $entryA = LedEntry::where('unit_id', $unitA->id)->where('objek_kode', 'SM-TEST-02')->firstOrFail();
        $stageA = LedEntryStage::where('led_entry_id', $entryA->id)->where('tahap', 'P2')->firstOrFail();
        $this->assertContains($promoted->id, $stageA->decodedBukti()['shared_document_ids']);
    }
}
