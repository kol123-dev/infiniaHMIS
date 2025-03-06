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
        Schema::create('bill_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id')->nullable();
            $table->integer('payment_type')->comment('1 Stripe, 2 Manual');
            $table->double('amount');
            $table->unsignedInteger('bill_id');
            $table->string('status')->nullable();
            $table->text('meta')->nullable();
            $table->boolean('is_manual_payment')->nullable();
            $table->timestamps();
            $table->foreign('bill_id')->references('id')->on('bills')->onUpdate('cascade')->onDelete('cascade');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bill_transactions');
    }
};
