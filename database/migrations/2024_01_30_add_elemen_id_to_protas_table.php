<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('protas', function (Blueprint $table) {
            $table->string('elemen_id')->nullable()->after('atp_id');
            $table->foreign('elemen_id')->references('kode')->on('elemens')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('protas', function (Blueprint $table) {
            $table->dropForeign(['elemen_id']);
            $table->dropColumn('elemen_id');
        });
    }
};

