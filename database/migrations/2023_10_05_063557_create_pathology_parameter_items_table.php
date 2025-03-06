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
        Schema::create('pathology_parameter_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('pathology_id');
            $table->text('patient_result');
            $table->unsignedInteger('parameter_id');
            $table->timestamps();

            $table->foreign('pathology_id')->references('id')->on('pathology_tests')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            $table->foreign('parameter_id')->references('id')->on('pathology_parameters')
            ->onDelete('cascade')
            ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pathology_parameter_items');
    }
};
