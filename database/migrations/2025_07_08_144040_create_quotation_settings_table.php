<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('quotation_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->decimal('asuransi_percent', 5, 2)->default(10.00);  // default 10%
            $table->decimal('margin_percent', 5, 2)->default(20.00);    // default 20%
            $table->decimal('biaya_pengiriman_laut_percent', 5, 2)->default(10.00); // default 10%
            $table->timestamps();
        });

        // Insert default row
        DB::table('quotation_settings')->insert([
            'asuransi_percent' => 10.00,
            'margin_percent' => 20.00,
            'biaya_pengiriman_laut_percent' => 10.00,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void {
        Schema::dropIfExists('quotation_settings');
    }
};
