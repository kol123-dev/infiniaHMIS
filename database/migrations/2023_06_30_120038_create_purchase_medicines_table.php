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
        Schema::create('purchase_medicines', function (Blueprint $table) {
            $table->id();
            $table->string('purchase_no');
            $table->float('tax', 25, 2);
            $table->float('total', 25, 2);
            $table->float('net_amount', 25, 2);
            $table->integer('payment_type');
            $table->float('discount', 25, 2);
            $table->string('note')->nullable();
            $table->string('payment_note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_medicines');
    }
};
