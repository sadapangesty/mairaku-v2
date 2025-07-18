<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJenisTable extends Migration
{
    public function up(): void
    {
        Schema::create('jenis', function (Blueprint $table) {
            $table->id('id_jenis');
            $table->string('nama');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jenis');
    }
}
