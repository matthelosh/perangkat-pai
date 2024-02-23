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
        Schema::create('materi_ajars', function (Blueprint $table) {
            $table->id();
            $table->enum('tingkat', ['1','2','3','4','5','6']);
            $table->enum('semester',['1','2']);
            $table->string('bab', 100);
            $table->string('label', 191);
            $table->enum('elemen', ['Al-Quran Hadits', 'Akidah', 'Akhlak','Fikih','Sejarah Peradaban Islam']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materi_ajars');
    }
};
