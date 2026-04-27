<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('team_members', function (Blueprint $table) {
            $table->string('structure_slot')
                ->nullable()
                ->after('slug')
                ->unique();
        });

        collect([
            'penasihat-yayasan' => 'advisor',
            'm-suaeb-abdullah' => 'trustee_primary',
            'anggota-dewan-pembina-1' => 'trustee_left',
            'anggota-dewan-pembina-2' => 'trustee_right',
            'direktur-yayasan' => 'director',
            'sekretaris-yayasan' => 'secretary',
            'bendahara-yayasan' => 'treasurer',
            'bidang-pendidikan' => 'field_education',
            'bidang-ekonomi' => 'field_economy',
            'bidang-lingkungan' => 'field_environment',
            'bidang-gender' => 'field_gender',
            'bidang-kesehatan' => 'field_health',
            'bidang-kebudayaan' => 'field_culture',
            'bidang-riset-digitalisasi' => 'field_research_digital',
        ])->each(function (string $slot, string $slug): void {
            DB::table('team_members')
                ->where('slug', $slug)
                ->update(['structure_slot' => $slot]);
        });
    }

    public function down(): void
    {
        Schema::table('team_members', function (Blueprint $table) {
            $table->dropUnique(['structure_slot']);
            $table->dropColumn('structure_slot');
        });
    }
};
