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
        Schema::create('tps', function (Blueprint $table) {
            $table->id();
            $table->string('elemen_id', 50);
            $table->string('guru_id', 50)->nullable();
            $table->string('kode', 30)->unique();
            $table->enum('fase', ['A', 'B', 'C', 'D', 'E', 'F']);
            $table->string('kompetensi', 191);
            $table->string('materi', 191);
            $table->text('teks');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tps');
    }
};
