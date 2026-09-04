<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alumni_info', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->unique()
                ->constrained()
                ->cascadeOnDelete();

             $table->string('member_id', 20)->nullable()->unique();

            $table->string('full_name');
            $table->string('mobile_number', 20);
            $table->text('address');
            $table->string('batch', 20);
            $table->string('passing_year', 10);
            $table->string('roll_number', 50)->nullable();
            $table->string('registration_number', 100)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alumni_info');
    }
};