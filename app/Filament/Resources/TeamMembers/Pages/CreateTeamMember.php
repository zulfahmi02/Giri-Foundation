<?php

namespace App\Filament\Resources\TeamMembers\Pages;

use App\Filament\Resources\TeamMembers\TeamMemberResource;
use App\Models\TeamMember;
use App\Support\TeamMemberStructureSlots;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateTeamMember extends CreateRecord
{
    protected static string $resource = TeamMemberResource::class;

    protected bool $replacedExistingStructureSlot = false;

    /**
     * @param  array<string, mixed>  $data
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return TeamMemberStructureSlots::applyToData($data);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function handleRecordCreation(array $data): Model
    {
        $existingRecord = filled($data['structure_slot'] ?? null)
            ? TeamMember::query()->where('structure_slot', $data['structure_slot'])->first()
            : null;

        $record = TeamMemberStructureSlots::upsert($data);

        $this->replacedExistingStructureSlot = $existingRecord !== null;

        return $record;
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return $this->replacedExistingStructureSlot
            ? 'Personil struktur berhasil diperbarui.'
            : 'Anggota tim berhasil dibuat.';
    }
}
