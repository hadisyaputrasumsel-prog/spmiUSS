<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EvidenceDocument extends Model
{
    protected $guarded = [];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'auto_attach' => 'boolean',
            'tahun' => 'integer',
        ];
    }

    /**
     * Unit owning this document when scoped to a specific unit.
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * User who uploaded or registered this document.
     */
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by_id');
    }

    /**
     * Restrict query to global documents and documents owned by the given unit.
     */
    public function scopeAccessibleByUnit(Builder $query, ?int $unitId): Builder
    {
        return $query->where(function (Builder $q) use ($unitId): void {
            $q->where('scope', 'global');
            if ($unitId !== null && $unitId > 0) {
                $q->orWhere(function (Builder $unitQuery) use ($unitId): void {
                    $unitQuery->where('scope', 'unit')
                        ->where('unit_id', $unitId);
                });
            }
        });
    }

    /**
     * Filter global documents configured to auto-attach for a standard, stage, and year.
     */
    public function scopeAutoAttachedFor(Builder $query, string $standarKode, string $tahap, ?int $tahun = null): Builder
    {
        return $query->where('scope', 'global')
            ->where('auto_attach', true)
            ->where(function (Builder $q) use ($standarKode): void {
                $q->whereNull('standar_kode')->orWhere('standar_kode', $standarKode);
            })
            ->where(function (Builder $q) use ($tahap): void {
                $q->whereNull('tahap')->orWhere('tahap', $tahap);
            })
            ->when($tahun !== null, function (Builder $q) use ($tahun): void {
                $q->where(function (Builder $yearQuery) use ($tahun): void {
                    $yearQuery->whereNull('tahun')->orWhere('tahun', $tahun);
                });
            });
    }

    /**
     * Return all auto-attached global documents indexed cleanly per stage for a standard.
     *
     * @return array<string, Collection<int, self>>
     */
    public static function autoAttachedByStage(string $standarKode, ?int $tahun = null): array
    {
        $docs = self::query()
            ->where('scope', 'global')
            ->where('auto_attach', true)
            ->where(function (Builder $q) use ($standarKode): void {
                $q->whereNull('standar_kode')->orWhere('standar_kode', $standarKode);
            })
            ->when($tahun !== null, function (Builder $q) use ($tahun): void {
                $q->where(function (Builder $yearQuery) use ($tahun): void {
                    $yearQuery->whereNull('tahun')->orWhere('tahun', $tahun);
                });
            })
            ->orderBy('kategori')
            ->orderBy('judul')
            ->get();

        $grouped = [];
        foreach (['P1', 'P2', 'P3', 'P4', 'P5'] as $tahap) {
            $grouped[$tahap] = $docs->filter(function (self $doc) use ($tahap): bool {
                return $doc->tahap === null || $doc->tahap === '' || $doc->tahap === $tahap;
            })->values();
        }

        return $grouped;
    }

    /**
     * Determine whether the given user is authorized to view or download this document.
     */
    public function canBeViewedBy(?User $user): bool
    {
        if ($user === null) {
            return false;
        }

        if ($this->scope === 'global') {
            return true;
        }

        $roleKode = $user->role->kode ?? '';
        if (in_array($roleKode, ['super_admin', 'lpma', 'pimpinan'], true)) {
            return true;
        }

        if ($user->unit_id !== null && (int) $user->unit_id === (int) $this->unit_id) {
            return true;
        }

        if ($user->isAuditor() && $this->unit_id !== null) {
            return AuditorAssignment::where('auditor_id', $user->id)
                ->where('unit_id', $this->unit_id)
                ->exists();
        }

        return false;
    }

    /**
     * Determine whether the given user is authorized to delete this document.
     */
    public function canBeManagedBy(?User $user): bool
    {
        if ($user === null) {
            return false;
        }

        $roleKode = $user->role->kode ?? '';
        if (in_array($roleKode, ['super_admin', 'lpma'], true)) {
            return true;
        }

        return $this->scope === 'unit'
            && $user->unit_id !== null
            && (int) $user->unit_id === (int) $this->unit_id
            && in_array($roleKode, ['auditee_upps', 'auditee_prodi', 'auditee_unit', 'gpm_upm'], true);
    }
}
