<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('logistics', function (Blueprint $table) {
            $table->bigIncrements('id_logistic');
            $table->string('name');
            $table->enum('type', ['Local', 'Internasional']);
            $table->enum('service', ['Reguler', 'Trucking', 'Sameday', 'Instant', 'LCL', 'FCL']);
            $table->string('phone')->nullable();
            $table->string('website')->nullable();
            $table->text('address')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('logistics');
    }
};
