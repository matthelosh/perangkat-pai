<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rombels', function (Blueprint $table) {
            $table->id();
            $table->string('sekolah_id', 30);
            $table->string('guru_id', 30);
            $table->string('kode', 100)->unique();
            $table->string('label', 100);
            $table->string('tapel', 15);
            $table->enum('fase', ['A','B','C']);
            $table->string('tingkat', 2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rombels');
    }
};
