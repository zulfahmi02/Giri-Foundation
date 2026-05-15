<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('donations')
            ->join('donors', 'donors.id', '=', 'donations.donor_id')
            ->select('donations.id as donation_id', 'donors.is_anonymous')
            ->orderBy('donations.id')
            ->chunk(100, function ($donations): void {
                foreach ($donations as $donation) {
                    DB::table('donations')
                        ->where('id', $donation->donation_id)
                        ->update([
                            'is_anonymous' => (bool) $donation->is_anonymous,
                        ]);
                }
            });
    }

    public function down(): void
    {
        //
    }
};
