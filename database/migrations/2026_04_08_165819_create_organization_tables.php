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
        Schema::create('organization_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('short_description');
            $table->longText('full_description');
            $table->text('vision');
            $table->text('mission');
            $table->text('values');
            $table->date('founded_date')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('whatsapp_number')->nullable();
            $table->text('address')->nullable();
            $table->text('google_maps_embed')->nullable();
            $table->text('logo_url')->nullable();
            $table->text('favicon_url')->nullable();
            $table->timestamps();
        });

        Schema::create('organization_stats', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->unsignedBigInteger('value');
            $table->string('suffix')->nullable();
            $table->string('icon')->nullable();
            $table->unsignedInteger('sort_order')->default(1);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('team_members', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('position');
            $table->string('division')->nullable();
            $table->text('bio')->nullable();
            $table->text('photo_url')->nullable();
            $table->string('email')->nullable();
            $table->text('linkedin_url')->nullable();
            $table->unsignedInteger('sort_order')->default(1);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key_name')->unique();
            $table->text('value_text')->nullable();
            $table->enum('value_type', ['text', 'number', 'boolean', 'json'])->default('text');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
        Schema::dropIfExists('team_members');
        Schema::dropIfExists('organization_stats');
        Schema::dropIfExists('organization_profiles');
    }
};
