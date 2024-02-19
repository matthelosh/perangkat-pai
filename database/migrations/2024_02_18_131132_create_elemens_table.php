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
        Schema::create('elemens', function (Blueprint $table) {
            $table->id();
            $table->integer('cp_id');
            $table->enum('fase', ['A','B','C','D','E','F']);
            $table->string('kode', 30);
            $table->string('label', 60);
            $table->text('deskripsi_cp');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('elemens');
    }
};
