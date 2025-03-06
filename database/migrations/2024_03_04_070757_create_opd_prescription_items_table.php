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
        Schema::create('opd_prescription_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('opd_prescription_id');
            $table->unsignedInteger('category_id');
            $table->unsignedInteger('medicine_id');
            $table->string('dosage');
            $table->integer('dose_interval');
            $table->string('day')->nullable();
            $table->string('time')->nullable();
            $table->text('instruction');
            $table->timestamps();

            $table->foreign('opd_prescription_id')->references('id')->on('opd_prescriptions')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('category_id')->references('id')->on('categories')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('medicine_id')->references('id')->on('medicines')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opd_prescription_items');
    }
};
