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
        Schema::create('protas', function (Blueprint $table) {
            $table->id();
            $table->string('guru_id', 35)->nullable();
            $table->string('rombel_id', 50);
            $table->string('atp_id', 30)->nullable();
            $table->date('tanggal');
            $table->enum('semester', ['1','2']);
            $table->string('minggu_ke', 1);
            $table->string('aw', 3);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('protas');
    }
};
