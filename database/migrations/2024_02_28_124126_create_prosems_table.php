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
        Schema::create('prosems', function (Blueprint $table) {
            $table->id();
            $table->integer('atp_id');
            $table->string('guru_id', 35)->nullable();
            $table->string('rombel_id', 60)->nullable();
            $table->string('tingkat', 3);
            $table->date('tanggal');
            $table->string('minggu_ke', 3);
            $table->string('hari', 30);
            $table->timestamps();
        });
    }

    // 'guru_id',
    // 'atp_id',
    // 'rombel_id',
    // 'tingkat',
    // 'tanggal',
    // 'minggu_ke',
    // 'hari'

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prosems');
    }
};
