<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('competition_phases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competition_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('phase_order');
            $table->string('bracket_type')->default('single_elim');
            $table->string('participant_source')->nullable(); // spin_wheel | all_registered | manual | survivors
            $table->unsignedInteger('participant_count')->default(0);
            $table->unsignedInteger('survivor_count')->nullable();
            $table->unsignedInteger('advancing_per_group')->nullable();
            $table->enum('status', ['pending', 'seeding', 'ongoing', 'finished'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('competition_phases');
    }
};
