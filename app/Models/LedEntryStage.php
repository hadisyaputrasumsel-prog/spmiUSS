<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $led_entry_id
 * @property string $tahap
 * @property string|null $tanggal
 * @property string|null $penanggung_jawab
 * @property string|null $uraian
 * @property string|null $catatan
 * @property string|null $bukti
 * @property array<array-key, mixed>|null $data_spesifik
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LedEntryStage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LedEntryStage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LedEntryStage query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LedEntryStage whereBukti($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LedEntryStage whereCatatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LedEntryStage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LedEntryStage whereDataSpesifik($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LedEntryStage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LedEntryStage whereLedEntryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LedEntryStage wherePenanggungJawab($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LedEntryStage whereTahap($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LedEntryStage whereTanggal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LedEntryStage whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LedEntryStage whereUraian($value)
 *
 * @mixin \Eloquent
 */
class LedEntryStage extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    protected $casts = [
        'data_spesifik' => 'array',
    ];

    /**
     * Decode the JSON bukti payload into normalized files, links, and shared document IDs.
     *
     * @return array{files: array<int, string>, links: array<int, string>, shared_document_ids: array<int, int>}
     */
    public function decodedBukti(): array
    {
        $decoded = is_string($this->bukti) ? json_decode($this->bukti, true) : null;
        if (! is_array($decoded)) {
            $decoded = [];
        }

        return [
            'files' => array_values(array_filter((array) ($decoded['files'] ?? []))),
            'links' => array_values(array_filter((array) ($decoded['links'] ?? []))),
            'shared_document_ids' => array_values(array_map('intval', (array) ($decoded['shared_document_ids'] ?? []))),
        ];
    }

    /**
     * Format tanggal as d/m/Y when stored as ISO date, otherwise return the period text as-is.
     */
    public function formattedTanggal(): string
    {
        $value = trim((string) ($this->tanggal ?? ''));
        if ($value === '') {
            return '-';
        }

        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value) === 1) {
            return Carbon::parse($value)->format('d/m/Y');
        }

        return $value;
    }
}
