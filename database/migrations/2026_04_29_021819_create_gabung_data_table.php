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
        Schema::create('gabung_data', function (Blueprint $table) {
            $table->id();
            $table->string('rm'); 
            $table->string('nama'); 
            $table->string('sep')->nullable();	
            $table->string('kelas')->nullable(); 	
            $table->string('naik')->nullable(); 	
            $table->string('ruang')->nullable();	
            $table->dateTime('masuk')->nullable();		
            $table->dateTime('keluar')->nullable();	
            $table->string('ket')->nullable();	
            $table->string('dpjp')->nullable(); 
            $table->string('kamar')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gabung_data');
    }
};
