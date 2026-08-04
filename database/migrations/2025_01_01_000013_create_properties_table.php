<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_id')->constrained('agents')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('categories')->restrictOnDelete();
            $table->foreignId('location_id')->constrained('locations')->restrictOnDelete();

            $table->string('title');
            $table->string('slug', 280)->unique();
            $table->longText('description');

            $table->enum('listing_type', ['sale', 'rent'])->index();
            $table->enum('status', ['available', 'pending', 'sold', 'rented'])->default('available')->index();

            $table->decimal('price', 14, 2);
            $table->decimal('area_sqft', 10, 2);
            $table->unsignedTinyInteger('bedrooms')->default(0);
            $table->unsignedTinyInteger('bathrooms')->default(0);
            $table->unsignedTinyInteger('floors')->default(1);
            $table->year('year_built')->nullable();

            $table->string('address', 500);
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            $table->string('virtual_tour_video')->nullable();
            $table->string('floor_plan_image')->nullable();
            $table->string('cover_image')->nullable();

            $table->boolean('is_featured')->default(false)->index();
            $table->unsignedInteger('views_count')->default(0);

            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'listing_type']);
            $table->fullText(['title', 'description']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
