<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->index(['status', 'published_at'], 'activities_status_published_idx');
            $table->index(['program_id', 'status', 'activity_date'], 'activities_program_status_date_idx');
        });

        Schema::table('contents', function (Blueprint $table) {
            $table->index(['status', 'type', 'published_at'], 'contents_status_type_published_idx');
            $table->index(['status', 'type', 'category_id', 'published_at'], 'contents_status_type_category_pub_idx');
        });

        Schema::table('documents', function (Blueprint $table) {
            $table->index(['is_public', 'published_at'], 'documents_public_published_idx');
            $table->index(['is_public', 'category', 'published_at'], 'documents_public_category_pub_idx');
        });

        Schema::table('donation_campaigns', function (Blueprint $table) {
            $table->index(['status', 'is_featured'], 'donation_campaigns_status_featured_idx');
        });

        Schema::table('partners', function (Blueprint $table) {
            $table->index(['is_active', 'name'], 'partners_active_name_idx');
        });

        Schema::table('program_partners', function (Blueprint $table) {
            $table->index(['program_id', 'partner_id'], 'program_partners_program_partner_idx');
        });

        Schema::table('programs', function (Blueprint $table) {
            $table->index(['status', 'phase', 'is_featured', 'published_at'], 'programs_status_phase_featured_idx');
            $table->index(['status', 'phase', 'start_date'], 'programs_status_phase_start_idx');
            $table->index(['status', 'phase', 'end_date', 'published_at'], 'programs_status_phase_end_pub_idx');
        });

        Schema::table('team_members', function (Blueprint $table) {
            $table->index(['is_active', 'is_structural', 'sort_order', 'parent_id'], 'team_members_active_structure_idx');
        });

        Schema::table('videos', function (Blueprint $table) {
            $table->index(['status', 'sort_order', 'published_at'], 'videos_status_sort_published_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->dropIndex('videos_status_sort_published_idx');
        });

        Schema::table('team_members', function (Blueprint $table) {
            $table->dropIndex('team_members_active_structure_idx');
        });

        Schema::table('programs', function (Blueprint $table) {
            $table->dropIndex('programs_status_phase_featured_idx');
            $table->dropIndex('programs_status_phase_start_idx');
            $table->dropIndex('programs_status_phase_end_pub_idx');
        });

        Schema::table('program_partners', function (Blueprint $table) {
            $table->dropIndex('program_partners_program_partner_idx');
        });

        Schema::table('partners', function (Blueprint $table) {
            $table->dropIndex('partners_active_name_idx');
        });

        Schema::table('donation_campaigns', function (Blueprint $table) {
            $table->dropIndex('donation_campaigns_status_featured_idx');
        });

        Schema::table('documents', function (Blueprint $table) {
            $table->dropIndex('documents_public_published_idx');
            $table->dropIndex('documents_public_category_pub_idx');
        });

        Schema::table('contents', function (Blueprint $table) {
            $table->dropIndex('contents_status_type_published_idx');
            $table->dropIndex('contents_status_type_category_pub_idx');
        });

        Schema::table('activities', function (Blueprint $table) {
            $table->dropIndex('activities_status_published_idx');
            $table->dropIndex('activities_program_status_date_idx');
        });
    }
};
