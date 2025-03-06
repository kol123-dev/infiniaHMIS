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
        Schema::create('sale_medicines', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('medicine_bill_id');
            $table->unsignedInteger('medicine_id');
            $table->integer('sale_quantity');
            $table->float('sale_price', 25, 2);
            $table->float('tax', 25, 2);
            $table->dateTime('expiry_date');
            $table->float('amount', 25, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_medicines');
    }
};
