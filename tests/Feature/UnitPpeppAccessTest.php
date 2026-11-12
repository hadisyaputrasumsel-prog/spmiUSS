<?php

namespace Tests\Feature;

use App\Models\AmiFinding;
use App\Models\Role;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UnitPpeppAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_can_navigate_to_any_unit_ppepp_cycle_from_dashboard_and_manajemen_auditee(): void
    {
        $superAdminRole = Role::forceCreate(['kode' => 'super_admin', 'nama' => 'Super Admin']);
        $superAdmin = User::factory()->create(['role_id' => $superAdminRole->id]);

        $unitWithFinding = Unit::forceCreate(['nama' => 'Program Studi Ilmu Komputer', 'jenis' => 'Program Studi']);
        $unitWithoutFinding = Unit::forceCreate(['nama' => 'Program Studi Manajemen', 'jenis' => 'Program Studi']);

        AmiFinding::forceCreate([
            'jenis' => 'akademik',
            'standar_kode' => 'S-01',
            'tahap' => 'P3',
            'unit_id' => $unitWithFinding->id,
            'kategori_temuan' => 'KTS Minor',
            'auditor_id' => $superAdmin->id,
            'tanggal' => '2023-08-07',
            'status_tindak_lanjut' => 'Belum',
        ]);

        $dashboard = $this->actingAs($superAdmin)->get(route('dashboard', ['tahun' => 2023]));
        $dashboard->assertOk();
        $dashboard->assertSee(route('manajemen-auditee.index', ['tahun' => 2023]), false);
        $dashboard->assertSee(route('unit.ppepp', ['id' => $unitWithFinding->id, 'tahun' => 2023]), false);

        $auditeeList = $this->actingAs($superAdmin)->get(route('manajemen-auditee.index', ['tahun' => 2023]));
        $auditeeList->assertOk();
        $auditeeList->assertSee('Siklus PPEPP');
        $auditeeList->assertSee(route('unit.ppepp', ['id' => $unitWithFinding->id, 'tahun' => 2023]), false);
        $auditeeList->assertSee(route('unit.ppepp', ['id' => $unitWithoutFinding->id, 'tahun' => 2023]), false);

        $ppeppPage = $this->actingAs($superAdmin)->get(route('unit.ppepp', ['id' => $unitWithoutFinding->id, 'tahun' => 2023]));
        $ppeppPage->assertOk();
        $ppeppPage->assertSee('Siklus PPEPP: Program Studi Manajemen (Tahun 2023)');
        $ppeppPage->assertSee('Evaluasi Diri');
        $ppeppPage->assertSee('Daftar Unit');
        $ppeppPage->assertSee(route('manajemen-auditee.index', ['tahun' => 2023]), false);
        $ppeppPage->assertSee(route('dashboard', ['tahun' => 2023]), false);
    }
}
