<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competition_id')->constrained()->cascadeOnDelete();
            $table->string('grow_id');
            $table->string('grow_id_normalized');
            $table->uuid('client_token');
            $table->string('ip_hash', 64);
            $table->boolean('flagged_duplicate')->default(false);
            $table->timestamp('created_at')->nullable();

            // 1 GrowID per competition
            $table->unique(['competition_id', 'grow_id_normalized']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
