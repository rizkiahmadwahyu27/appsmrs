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
        Schema::create('data_pasiens', function (Blueprint $table) {
            $table->id();
            $table->string('rm');
            $table->string('nama');
            $table->string('no_sep')->nullable();
            $table->integer('kelas')->nullable();
            $table->string('naik_kelas')->nullable();
            $table->string('status')->nullable();
            $table->datetime('tgl_masuk')->nullable();
            $table->datetime('tgl_keluar')->nullable();
            $table->string('dpjp')->nullable();
            $table->string('ruangan')->nullable();
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_pasiens');
    }
};
