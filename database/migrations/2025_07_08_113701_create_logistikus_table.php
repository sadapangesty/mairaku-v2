<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('logistikus', function (Blueprint $table) {
            $table->bigIncrements('id_logistiku');
            $table->string('kode')->unique();
            $table->string('nama_logistiku');
            $table->unsignedBigInteger('id_logistic'); // FK ke logistics
            $table->enum('jenis', ['laut', 'udara']);
            $table->bigInteger('harga_vendor');
            $table->bigInteger('harga_jual');
            $table->date('tanggal_aktif');
            $table->date('tanggal_nonaktif')->nullable();
            $table->timestamps();

            // Foreign key
            $table->foreign('id_logistic')
                  ->references('id_logistic')
                  ->on('logistics')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('logistikus');
    }
};
