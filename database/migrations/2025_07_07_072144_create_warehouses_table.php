<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();                                // id primary key
            $table->string('code', 20)->unique();        // kode gudang unik
            $table->string('name');                      // nama gudang
            $table->string('country', 50);               // negara
            $table->string('city')->nullable();          // kota (opsional)
            $table->string('phone')->nullable();         // nomor telepon (opsional)
            $table->text('address')->nullable();         // alamat (opsional)
            $table->unsignedBigInteger('currency_id');   // relasi ke currencies
            $table->text('note')->nullable();            // catatan (opsional)
            $table->boolean('is_active')->default(true); // status aktif

            $table->timestamps();

            // Foreign key constraint
            $table->foreign('currency_id')
                  ->references('id_currency')      // pastikan sesuai nama PK di tabel currencies
                  ->on('currencies')
                  ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warehouses');
    }
};
