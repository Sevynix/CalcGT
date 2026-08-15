<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('manual_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competition_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('name_normalized');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('ffa_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competition_id')->constrained()->cascadeOnDelete();
            $table->foreignId('phase_id')->constrained('competition_phases')->cascadeOnDelete();
            $table->string('participant_name');
            $table->boolean('passed')->default(false);
            $table->timestamps();

            $table->unique(['phase_id', 'participant_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ffa_results');
        Schema::dropIfExists('manual_participants');
    }
};
