<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bug_status_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bug_id')->constrained('bugs')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('old_status', ['OPEN', 'IN_PROGRESS', 'RESOLVED', 'CLOSED'])->nullable();
            $table->enum('new_status', ['OPEN', 'IN_PROGRESS', 'RESOLVED', 'CLOSED']);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bug_status_histories');
    }
};
