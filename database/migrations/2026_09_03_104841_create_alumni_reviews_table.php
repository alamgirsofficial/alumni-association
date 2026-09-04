<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alumni_reviews', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            $table->foreignId('admin_id')->nullable()->constrained('users')->nullOnDelete();
            //nullOnDelete() rakar karon holo user/admin delete holeo workHistory delete hobe na

            $table->enum('action', ['pending', 'approved', 'rejected',]);
            $table->text('message')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alumni_reviews');
    }
};
