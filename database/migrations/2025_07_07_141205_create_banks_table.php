<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('banks', function (Blueprint $table) {
            $table->id('id_bank');
            $table->string('nama_bank');
            $table->string('atas_nama');
            $table->string('no_rekening');
        });
    }

    public function down(): void {
        Schema::dropIfExists('banks');
    }
};
