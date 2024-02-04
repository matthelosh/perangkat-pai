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
        Schema::create('sekolahs', function (Blueprint $table) {
            $table->id();
            $table->string('npsn', 15)->unique();
            $table->string("nss", 60)->nullable();
            $table->string("nama", 100);
            $table->string("alamat", 191);
            $table->string("rt", 3)->nullable();
            $table->string("rw", 3)->nullable();
            $table->string("desa", 100)->nullable();
            $table->string("kecamatan", 60)->default("Wagir");
            $table->string("kode_pos", 60)->default("65158");
            $table->string("kabupaten", 60)->default("Malang");
            $table->string("telp", 20)->nullable();
            $table->string("email", 191)->nullable();
            $table->string("website", 191)->nullable();
            $table->string("logo", 191)->nullable();
            $table->string("nama_ks", 100)->nullable();
            $table->string("nip_ks", 30)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sekolahs');
    }
};
