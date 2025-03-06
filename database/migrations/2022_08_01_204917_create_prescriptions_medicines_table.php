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
        Schema::create('prescriptions_medicines', function (Blueprint $table) {
            $table->id();
            $table->integer('prescription_id')->unsigned();
            $table->integer('medicine')->unsigned();
            $table->string('dosage')->nullable();
            $table->string('day')->nullable();
            $table->string('time')->nullable();
            $table->string('comment')->nullable();
            $table->foreign('prescription_id')->on('prescriptions')->references('id')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('medicine')->on('medicines')->references('id')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('prescriptions_medicines');
    }
};
