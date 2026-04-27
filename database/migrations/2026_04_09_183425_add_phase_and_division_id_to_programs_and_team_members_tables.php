<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('programs', function (Blueprint $table) {
            $table->enum('phase', ['active', 'upcoming', 'archived'])->default('active')->after('status');
        });

        Schema::table('team_members', function (Blueprint $table) {
            $table->foreignId('division_id')->nullable()->after('division')->constrained('divisions')->nullOnDelete();
        });

        $now = now();
        $divisionNames = DB::table('team_members')
            ->select('division')
            ->whereNotNull('division')
            ->where('division', '!=', '')
            ->distinct()
            ->orderBy('division')
            ->pluck('division');

        foreach ($divisionNames as $index => $divisionName) {
            DB::table('divisions')->updateOrInsert(
                ['slug' => Str::slug($divisionName)],
                [
                    'name' => $divisionName,
                    'description' => null,
                    'sort_order' => $index + 1,
                    'is_active' => true,
                    'updated_at' => $now,
                    'created_at' => $now,
                ],
            );
        }

        foreach ($divisionNames as $divisionName) {
            $divisionId = DB::table('divisions')
                ->where('slug', Str::slug($divisionName))
                ->value('id');

            DB::table('team_members')
                ->where('division', $divisionName)
                ->update(['division_id' => $divisionId]);
        }

        DB::table('programs')
            ->whereIn('status', ['completed', 'archived'])
            ->update([
                'status' => 'published',
                'phase' => 'archived',
            ]);

        DB::table('programs')
            ->whereNotIn('status', ['completed', 'archived'])
            ->update(['phase' => 'active']);

        DB::table('programs')
            ->whereNotNull('start_date')
            ->whereDate('start_date', '>', now()->toDateString())
            ->update(['phase' => 'upcoming']);

        DB::table('programs')
            ->whereNotNull('end_date')
            ->whereDate('end_date', '<', now()->toDateString())
            ->update(['phase' => 'archived']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('team_members', function (Blueprint $table) {
            $table->dropForeign(['division_id']);
            $table->dropColumn('division_id');
        });

        Schema::table('programs', function (Blueprint $table) {
            $table->dropColumn('phase');
        });
    }
};
