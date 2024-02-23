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
        Schema::create('atps', function (Blueprint $table) {
            $table->id();
            $table->string('guru_id', 30)->nullable();
            $table->string('kode', 60)->unique();
            $table->string('elemen_id', 30);
            $table->enum('tingkat', ['1','2','3','4','5','6']);
            $table->enum('fase', ['A','B','C']);
            $table->enum('semester', ['1','2']);
            $table->string('aw', 3);
            $table->text('materi');
            $table->text('tps');
            $table->text('asesmen');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('atps');
    }
};
