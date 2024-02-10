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
        Schema::create('gurus', function (Blueprint $table) {
            $table->id();
            $table->string('sekolah_id', 30);
            $table->string('nip', 30)->nullable();
            $table->string('belajar_id', 191)->nullable();
            $table->string('nuptk', 30)->nullable();
            $table->string('no_sertifikat', 30)->nullable();
            $table->string('nrg', 30)->nullable();
            $table->string('gelar_depan', 30)->nullable();
            $table->string('gelar_belakang', 30)->nullable();
            $table->string('nama', 100);
            $table->string('alamat', 191)->nullable();
            $table->string('hp', 20)->nullable();
            $table->string('foto', 191)->nullable();
            $table->enum('jk', ['Laki-laki', 'Perempuan']);
            $table->enum('status_kepegawaian', ['pns', 'p3k', 'gtt']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gurus');
    }
};
