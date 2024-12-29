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
            $table->string('guru_id', 30)->nullable();
            $table->string('rombel_id', 50)->nullable();
            $table->text('p5');
            $table->text('tps');
            $table->text('media');
            $table->text('pemahaman');
            $table->text('pertanyaan');
            $table->text('pendahuluan');
            $table->text('inti');
            $table->text('penutup');
            $table->text('asesmen');
            $table->text('referensi');
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
