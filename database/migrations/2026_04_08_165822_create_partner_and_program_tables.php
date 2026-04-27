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
        Schema::create('program_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('partners', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('logo_url')->nullable();
            $table->text('website_url')->nullable();
            $table->enum('type', ['foundation', 'ngo', 'corporate', 'community', 'government', 'media'])->default('ngo');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('description');
            $table->foreignId('category_id')->nullable()->constrained('program_categories')->nullOnDelete();
            $table->enum('status', ['draft', 'published', 'completed', 'archived'])->default('draft');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('location_name')->nullable();
            $table->string('province')->nullable();
            $table->string('city')->nullable();
            $table->string('target_beneficiaries')->nullable();
            $table->unsignedInteger('beneficiaries_count')->default(0);
            $table->decimal('budget_amount', 14, 2)->nullable();
            $table->text('featured_image_url')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('program_galleries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained()->cascadeOnDelete();
            $table->text('file_url');
            $table->string('caption')->nullable();
            $table->unsignedInteger('sort_order')->default(1);
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('program_partners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained()->cascadeOnDelete();
            $table->foreignId('partner_id')->constrained()->cascadeOnDelete();
            $table->string('role')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->unique(['program_id', 'partner_id']);
        });

        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('summary')->nullable();
            $table->longText('description');
            $table->date('activity_date')->nullable();
            $table->string('location_name')->nullable();
            $table->text('featured_image_url')->nullable();
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
        Schema::dropIfExists('program_partners');
        Schema::dropIfExists('program_galleries');
        Schema::dropIfExists('programs');
        Schema::dropIfExists('partners');
        Schema::dropIfExists('program_categories');
    }
};
