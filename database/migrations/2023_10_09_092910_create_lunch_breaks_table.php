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
        Schema::create('lunch_breaks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('doctor_id');
            $table->time('break_from');
            $table->time('break_to');
            $table->boolean('every_day')->nullable();
            $table->string('date')->nullable();
            $table->timestamps();

            $table->foreign('doctor_id')->references('id')->on('doctors')
            ->onDelete('cascade')
            ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lunch_breaks');
    }
};
