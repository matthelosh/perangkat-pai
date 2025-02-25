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
        Schema::create('jurnals', function (Blueprint $table) {
            $table->id();
            $table->integer('atp_id');
            $table->string('guru_id', 30)->nullable();
            $table->string('rombel_id', 40)->nullable();
            $table->enum('semester', ['1', '2']);
            $table->enum('tingkat', ['1', '2', '3', '4', '5', '6']);
            $table->string('foto', 191)->nullable();
            $table->text('absensi');
            $table->text('catatan');
            $table->date('tanggal');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jurnals');
    }
};
