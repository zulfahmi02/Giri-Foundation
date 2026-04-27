<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('content_categories', function (Blueprint $table) {
            $table->enum('type', ['story', 'article', 'news', 'report', 'journal', 'opinion'])
                ->default('story')
                ->change();
        });

        Schema::table('contents', function (Blueprint $table) {
            $table->enum('type', ['story', 'article', 'news', 'report', 'journal', 'opinion'])
                ->default('story')
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('content_categories')
            ->whereIn('type', ['journal', 'opinion'])
            ->update(['type' => 'article']);

        DB::table('contents')
            ->whereIn('type', ['journal', 'opinion'])
            ->update(['type' => 'article']);

        Schema::table('content_categories', function (Blueprint $table) {
            $table->enum('type', ['story', 'article', 'news', 'report'])
                ->default('story')
                ->change();
        });

        Schema::table('contents', function (Blueprint $table) {
            $table->enum('type', ['story', 'article', 'news', 'report'])
                ->default('story')
                ->change();
        });
    }
};
