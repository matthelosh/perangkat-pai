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
        Schema::create('modul_ajars', function (Blueprint $table) {
            $table->id();
            $table->integer('atp_id');
            $table->string('guru_id', 30);
            $table->string('rombel_id', 50);
            $table->text('kompetensi_awal');
            $table->text('p5');
            $table->text('sarpras');
            $table->string('target_siswa', 191);
            $table->string('model', 191);
            $table->text('tps');
            $table->text('cps');
            $table->text('pemahaman');
            $table->text('pertanyaan');
            $table->text('persiapan');
            $table->text('pendahuluan');
            $table->text('inti');
            $table->text('penutup');
            $table->text('remidial');
            $table->text('pengayaan');
            $table->text('refleksi_guru');
            $table->text('refleksi_siswa');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modul_ajars');
    }
};
