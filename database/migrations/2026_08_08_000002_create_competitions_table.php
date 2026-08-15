<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('competitions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tournament_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('detail')->nullable();
            $table->string('photo_path')->nullable();
            $table->unsignedInteger('reveal_order')->default(1);
            $table->unsignedInteger('slot_total')->default(8);
            $table->unsignedTinyInteger('slot_count')->nullable();
            $table->boolean('has_registration')->default(false);
            $table->boolean('has_bracket')->default(true);
            $table->string('bracket_format')->nullable();
            $table->unsignedInteger('phase2_target_count')->nullable();
            $table->enum('status', [
                'hidden',
                'revealed',
                'registration_open',
                'registration_closed',
                'ongoing',
                'finished',
            ])->default('hidden');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('competitions');
    }
};
