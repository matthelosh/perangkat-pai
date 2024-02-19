<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("kaldiks", function (Blueprint $table) {
            $table->id();
            $table->integer("tapel_id");
            $table->string("label", 100);
            $table->date("mulai");
            $table->date("selesai");
            $table->text("deskripsi");
            $table->boolean("is_libur")->default(true);
            $table->string("warna", 50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("kaldiks");
    }
};
