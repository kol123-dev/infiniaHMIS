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
        Schema::create('pathology_parameters', function (Blueprint $table) {
            $table->increments('id');
            $table->string('parameter_name');
            $table->string('reference_range');
            $table->unsignedInteger('unit_id');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('unit_id')->references('id')->on('pathology_units')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pathology_parameters');
    }
};
