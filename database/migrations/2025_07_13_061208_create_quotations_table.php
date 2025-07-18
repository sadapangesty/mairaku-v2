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
        Schema::create('quotations', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->autoIncrement();
            $table->string('quotation_number')->unique();
            $table->string('tax')->nullable();
            $table->date('quotation_date');
            $table->date('quotation_expiry_date');
            $table->decimal('quotation_price', 15, 2)->default(0);
            $table->decimal('quotation_shipping_cost', 15, 2)->default(0);
            $table->decimal('quotation_discount', 15, 2)->default(0);
            $table->decimal('quotation_total', 15, 2)->default(0);
            $table->int('customer_id')->unsigned();
            $table->integer('bank_id')->unsigned()->nullable();
            $table->string('payment_method')->nullable();
            $table->foreign('customer_id')->references('id_customer')->on('customers')->onCascade();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
};
