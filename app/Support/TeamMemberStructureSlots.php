<?php

namespace App\Support;

use App\Models\Division;
use App\Models\TeamMember;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TeamMemberStructureSlots
{
    public const Advisor = 'advisor';

    public const TrusteePrimary = 'trustee_primary';

    public const TrusteeLeft = 'trustee_left';

    public const TrusteeRight = 'trustee_right';

    public const Director = 'director';

    public const Secretary = 'secretary';

    public const Treasurer = 'treasurer';

    public const FieldEducation = 'field_education';

    public const FieldEconomy = 'field_economy';

    public const FieldEnvironment = 'field_environment';

    public const FieldGender = 'field_gender';

    public const FieldHealth = 'field_health';

    public const FieldCulture = 'field_culture';

    public const FieldResearchDigital = 'field_research_digital';

    /**
     * @return array<string, array{label: string, display_label: string, position: string, division_slug: string, parent_slot: string|null, sort_order: int}>
     */
    public static function definitions(): array
    {
        return [
            self::Advisor => [
                'label' => 'Penasihat',
                'display_label' => 'Penasihat',
                'position' => 'Penasihat',
                'division_slug' => 'dewan-penasihat',
                'parent_slot' => null,
                'sort_order' => 1,
            ],
            self::TrusteePrimary => [
                'label' => 'Ketua Pembina',
                'display_label' => 'Ketua',
                'position' => 'Ketua Dewan Pembina',
                'division_slug' => 'dewan-pembina',
                'parent_slot' => self::Advisor,
                'sort_order' => 20,
            ],
            self::TrusteeLeft => [
                'label' => 'Anggota Pembina (kiri)',
                'display_label' => 'Anggota',
                'position' => 'Anggota Dewan Pembina',
                'division_slug' => 'dewan-pembina',
                'parent_slot' => self::Advisor,
                'sort_order' => 10,
            ],
            self::TrusteeRight => [
                'label' => 'Anggota Pembina (kanan)',
                'display_label' => 'Anggota',
                'position' => 'Anggota Dewan Pembina',
                'division_slug' => 'dewan-pembina',
                'parent_slot' => self::Advisor,
                'sort_order' => 30,
            ],
            self::Director => [
                'label' => 'Direktur',
                'display_label' => 'Direktur',
                'position' => 'Direktur',
                'division_slug' => 'dewan-pengurus',
                'parent_slot' => self::TrusteePrimary,
                'sort_order' => 50,
            ],
            self::Secretary => [
                'label' => 'Sekretaris',
                'display_label' => 'Sekretaris',
                'position' => 'Sekretaris Yayasan',
                'division_slug' => 'dewan-pengurus',
                'parent_slot' => self::Director,
                'sort_order' => 60,
            ],
            self::Treasurer => [
                'label' => 'Bendahara',
                'display_label' => 'Bendahara',
                'position' => 'Bendahara Yayasan',
                'division_slug' => 'dewan-pengurus',
                'parent_slot' => self::Director,
                'sort_order' => 70,
            ],
            self::FieldEducation => [
                'label' => 'Bidang Pendidikan',
                'display_label' => 'Bidang Pendidikan',
                'position' => 'Bidang Pendidikan',
                'division_slug' => 'bidang-kajian',
                'parent_slot' => self::Director,
                'sort_order' => 80,
            ],
            self::FieldEconomy => [
                'label' => 'Bidang Ekonomi',
                'display_label' => 'Bidang Ekonomi',
                'position' => 'Bidang Ekonomi',
                'division_slug' => 'bidang-kajian',
                'parent_slot' => self::Director,
                'sort_order' => 81,
            ],
            self::FieldEnvironment => [
                'label' => 'Bidang Lingkungan',
                'display_label' => 'Bidang Lingkungan',
                'position' => 'Bidang Lingkungan',
                'division_slug' => 'bidang-kajian',
                'parent_slot' => self::Director,
                'sort_order' => 82,
            ],
            self::FieldGender => [
                'label' => 'Bidang Gender',
                'display_label' => 'Bidang Gender',
                'position' => 'Bidang Gender',
                'division_slug' => 'bidang-kajian',
                'parent_slot' => self::Director,
                'sort_order' => 83,
            ],
            self::FieldHealth => [
                'label' => 'Bidang Kesehatan',
                'display_label' => 'Bidang Kesehatan',
                'position' => 'Bidang Kesehatan',
                'division_slug' => 'bidang-kajian',
                'parent_slot' => self::Director,
                'sort_order' => 84,
            ],
            self::FieldCulture => [
                'label' => 'Bidang Kebudayaan',
                'display_label' => 'Bidang Kebudayaan',
                'position' => 'Bidang Kebudayaan',
                'division_slug' => 'bidang-kajian',
                'parent_slot' => self::Director,
                'sort_order' => 85,
            ],
            self::FieldResearchDigital => [
                'label' => 'Bidang Riset dan Digitalisasi',
                'display_label' => 'Bidang Riset dan Digitalisasi',
                'position' => 'Bidang Riset dan Digitalisasi',
                'division_slug' => 'bidang-kajian',
                'parent_slot' => self::Director,
                'sort_order' => 86,
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return collect(self::definitions())
            ->mapWithKeys(fn (array $definition, string $slot): array => [$slot => $definition['label']])
            ->all();
    }

    public static function label(?string $slot): ?string
    {
        return self::definitions()[$slot]['label'] ?? null;
    }

    public static function displayLabel(?string $slot): ?string
    {
        return self::definitions()[$slot]['display_label'] ?? null;
    }

    public static function displayRoleLabel(?TeamMember $teamMember): ?string
    {
        if ($teamMember === null) {
            return null;
        }

        return self::displayLabel($teamMember->structure_slot) ?? $teamMember->position;
    }

    public static function usesLateralLayout(?TeamMember $teamMember): bool
    {
        return $teamMember?->structure_slot === self::Advisor;
    }

    /**
     * @return array<string, int|string|null>
     */
    public static function previewAttributes(?string $slot): array
    {
        if (blank($slot)) {
            return [];
        }

        $definition = self::definitions()[$slot] ?? null;

        if ($definition === null) {
            return [];
        }

        $division = Division::query()
            ->where('slug', $definition['division_slug'])
            ->first();

        $parent = self::recordForSlot($definition['parent_slot']);

        return [
            'position' => $definition['position'],
            'division_id' => $division?->id,
            'parent_id' => $parent?->id,
            'sort_order' => $definition['sort_order'],
        ];
    }

    public static function selectionHelperText(?string $slot): string
    {
        if (blank($slot)) {
            return 'Pilih slot jabatan sesuai bagan organisasi. Sistem akan mengatur posisi, divisi, atasan, dan urutan tampil secara otomatis.';
        }

        $occupiedRecord = self::recordForSlot($slot);

        if ($occupiedRecord === null) {
            return 'Slot ini masih kosong dan akan diisi oleh personil yang Anda simpan.';
        }

        return "Slot ini saat ini ditempati oleh {$occupiedRecord->name}. Menyimpan form akan memperbarui personil pada slot tersebut, bukan membuat slot baru.";
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public static function applyToData(array $data, ?TeamMember $record = null): array
    {
        if (! ($data['is_structural'] ?? false)) {
            $data['structure_slot'] = null;
            $data['division'] = filled($data['division_id'] ?? null)
                ? Division::query()->find($data['division_id'])?->name
                : null;

            if (blank($data['slug'] ?? null) && filled($data['name'] ?? null)) {
                $data['slug'] = self::uniqueSlug((string) $data['name'], $record);
            }

            return $data;
        }

        $slot = $data['structure_slot'] ?? $record?->structure_slot;
        $definition = self::definitions()[$slot] ?? null;

        if ($definition === null) {
            return $data;
        }

        $division = Division::query()
            ->where('slug', $definition['division_slug'])
            ->first();

        $occupiedRecord = self::recordForSlot($slot, $record);
        $slugOwner = $occupiedRecord ?? $record;

        return [
            ...$data,
            'structure_slot' => $slot,
            'slug' => self::uniqueSlug((string) $data['name'], $slugOwner),
            'position' => $definition['position'],
            'division' => $division?->name,
            'division_id' => $division?->id,
            'parent_id' => self::recordForSlot($definition['parent_slot'])?->id,
            'sort_order' => $definition['sort_order'],
            'is_structural' => true,
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function upsert(array $data, ?TeamMember $record = null): TeamMember
    {
        $data = self::applyToData($data, $record);

        if (! ($data['is_structural'] ?? false) || blank($data['structure_slot'] ?? null)) {
            $targetRecord = $record ?? new TeamMember();
            $targetRecord->fill($data);
            $targetRecord->save();

            return $targetRecord;
        }

        $targetRecord = self::recordForSlot($data['structure_slot'], $record) ?? $record ?? new TeamMember();

        $targetRecord->fill($data);
        $targetRecord->save();

        return $targetRecord;
    }

    private static function recordForSlot(?string $slot, ?Model $ignoreRecord = null): ?TeamMember
    {
        if (blank($slot)) {
            return null;
        }

        return TeamMember::query()
            ->where('structure_slot', $slot)
            ->when($ignoreRecord, fn ($query) => $query->whereKeyNot($ignoreRecord->getKey()))
            ->first();
    }

    private static function uniqueSlug(string $name, ?Model $ignoreRecord = null): string
    {
        $baseSlug = Str::slug($name);
        $baseSlug = filled($baseSlug) ? $baseSlug : 'anggota-tim';
        $candidate = $baseSlug;
        $counter = 2;

        while (TeamMember::query()
            ->when($ignoreRecord, fn ($query) => $query->whereKeyNot($ignoreRecord->getKey()))
            ->where('slug', $candidate)
            ->exists()) {
            $candidate = "{$baseSlug}-{$counter}";
            $counter++;
        }

        return $candidate;
    }
}
