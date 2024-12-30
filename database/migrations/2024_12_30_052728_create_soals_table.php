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
        Schema::create('soals', function (Blueprint $table) {
            $table->id();
            $table->enum('tingkat', ['1', '2', '3', '4', '5', '6']);
            $table->string('tp_id', 50);
            $table->string('guru_id', 30)->nullable();
            $table->string('agama', 20)->default('Islam');
            $table->enum('tipe', ['pilihan', 'isian', 'uraian'])->default('pilihan');
            $table->enum('level', ['lot', 'mot', 'hot'])->default('lot');
            $table->enum('semester', ['1', '2']);
            $table->text('pertanyaan');
            $table->text('a');
            $table->text('b')->nullable();
            $table->text('c')->nullable();
            $table->text('d')->nullable();
            $table->text('kunci');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('soals');
    }
};
