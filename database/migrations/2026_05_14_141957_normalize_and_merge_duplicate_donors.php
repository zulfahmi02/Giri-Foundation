<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        DB::transaction(function (): void {
            $duplicateEmails = DB::table('donors')
                ->selectRaw('LOWER(TRIM(email)) as normalized_email')
                ->groupByRaw('LOWER(TRIM(email))')
                ->havingRaw('COUNT(*) > 1')
                ->pluck('normalized_email');

            foreach ($duplicateEmails as $normalizedEmail) {
                $donors = DB::table('donors')
                    ->whereRaw('LOWER(TRIM(email)) = ?', [$normalizedEmail])
                    ->orderBy('id')
                    ->get();

                $canonicalDonor = $donors->first();

                if (! $canonicalDonor) {
                    continue;
                }

                $mergedFullName = collect($donors)
                    ->pluck('full_name')
                    ->first(static fn (?string $fullName): bool => filled($fullName));

                $mergedPhone = collect($donors)
                    ->pluck('phone')
                    ->first(static fn (?string $phone): bool => filled($phone));

                DB::table('donors')
                    ->where('id', $canonicalDonor->id)
                    ->update([
                        'email' => $normalizedEmail,
                        'full_name' => $mergedFullName ?? $canonicalDonor->full_name,
                        'phone' => $canonicalDonor->phone ?: $mergedPhone,
                        'updated_at' => now(),
                    ]);

                $duplicateDonorIds = collect($donors)
                    ->skip(1)
                    ->pluck('id');

                if ($duplicateDonorIds->isEmpty()) {
                    continue;
                }

                DB::table('donations')
                    ->whereIn('donor_id', $duplicateDonorIds)
                    ->update([
                        'donor_id' => $canonicalDonor->id,
                    ]);

                DB::table('donors')
                    ->whereIn('id', $duplicateDonorIds)
                    ->delete();
            }

            DB::table('donors')
                ->orderBy('id')
                ->chunk(100, function ($donors): void {
                    foreach ($donors as $donor) {
                        $normalizedEmail = Str::lower(trim((string) $donor->email));

                        if ($normalizedEmail === $donor->email) {
                            continue;
                        }

                        DB::table('donors')
                            ->where('id', $donor->id)
                            ->update([
                                'email' => $normalizedEmail,
                                'updated_at' => now(),
                            ]);
                    }
                });
        }, 5);
    }

    public function down(): void
    {
        //
    }
};
