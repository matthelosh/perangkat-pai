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
        Schema::create('asesmens', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 60)->unique();
            $table->string('label');
            $table->enum('tingkat', ['1', '2', '3', '4', '5', '6']);
            $table->enum('tipe', ['uh', 'pts', 'pas']);
            $table->string('tapel', 20);
            $table->enum('semester', ['1', '2']);
            $table->string('jml_soal');
            $table->string('kunci');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asesmens');
    }
};
