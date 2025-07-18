<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barangs', function (Blueprint $table) {
            $table->id(); // id default: unsignedBigInteger auto increment
            $table->string('nama_barang');
            $table->unsignedBigInteger('id_kategori');
            $table->unsignedBigInteger('id_jenis');
            $table->string('sku')->unique();
            $table->decimal('harga', 12, 2);
            $table->string('link_barang')->nullable();
            $table->string('foto')->default('default-product.png');
            $table->timestamps();

            // FK: pastikan nama tabel benar!
            $table->foreign('id_kategori')->references('id_kategori')->on('kategoris')->onDelete('cascade');
            $table->foreign('id_jenis')->references('id_jenis')->on('jenis')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};
