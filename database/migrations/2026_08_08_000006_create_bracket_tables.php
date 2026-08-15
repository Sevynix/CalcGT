<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('competition_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('phase_id')->constrained('competition_phases')->cascadeOnDelete();
            $table->string('name');
            $table->json('standings')->nullable();
            $table->timestamps();
        });

        Schema::create('bracket_nodes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('phase_id')->constrained('competition_phases')->cascadeOnDelete();
            $table->string('bracket_side')->default('upper'); // upper | lower | grand_final
            $table->unsignedInteger('round');
            $table->unsignedInteger('position');
            $table->string('participant_a')->nullable();
            $table->string('participant_b')->nullable();
            $table->string('winner')->nullable();
            $table->boolean('is_bye')->default(false);
            $table->boolean('locked')->default(false);
            // Group stage
            $table->foreignId('group_id')->nullable()->constrained('competition_groups')->cascadeOnDelete();
            // Routing for upper/lower bracket
            $table->unsignedBigInteger('loser_routes_to_node_id')->nullable();
            $table->string('loser_routes_to_slot')->nullable();
            $table->unsignedBigInteger('winner_routes_to_node_id')->nullable();
            $table->string('winner_routes_to_slot')->nullable();
            $table->boolean('is_if_necessary')->default(false);
            $table->timestamps();

            $table->index(['phase_id', 'round', 'position']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bracket_nodes');
        Schema::dropIfExists('competition_groups');
    }
};
