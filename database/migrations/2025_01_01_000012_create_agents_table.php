<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->string('agency_name')->nullable();
            $table->string('license_no', 100)->nullable();
            $table->text('bio')->nullable();
            $table->string('whatsapp', 20)->nullable();
            $table->unsignedTinyInteger('experience_years')->default(0);
            $table->decimal('rating', 2, 1)->default(0);
            $table->boolean('is_verified')->default(false)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agents');
    }
};
