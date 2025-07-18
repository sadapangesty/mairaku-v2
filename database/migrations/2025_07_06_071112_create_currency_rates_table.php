<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('currency_rates', function (Blueprint $table) {
            $table->id('id_currency_rate');
            $table->unsignedBigInteger('from_currency_id');
            $table->unsignedBigInteger('to_currency_id');
            $table->string('rate_category'); // internal, money_changer, markup
            $table->string('rate_type');     // selling, buy, middle
            $table->decimal('rate', 16, 6);
            $table->date('effective_date');
            // Foreign key
            $table->foreign('from_currency_id')->references('id_currency')->on('currencies');
            $table->foreign('to_currency_id')->references('id_currency')->on('currencies');
        });
    }

    public function down(): void {
        Schema::dropIfExists('currency_rates');
    }
};
