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
        Schema::create('medicine_bills', function (Blueprint $table) {
            $table->id();
            $table->string('bill_number');
            $table->unsignedInteger('patient_id');
            $table->unsignedInteger('doctor_id')->nullable();
            $table->string('model_type');
            $table->string('model_id');
            $table->float('discount', 25, 2);
            $table->float('net_amount', 25, 2);
            $table->float('total', 25, 2);
            $table->float('tax_amount', 25, 2);
            $table->integer('payment_status');
            $table->integer('payment_type');
            $table->string('note')->nullable();
            $table->datetime('bill_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicine_bills');
    }
};
