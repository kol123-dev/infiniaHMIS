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
        Schema::create('opd_diagnoses', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('opd_patient_department_id');
            $table->string('report_type');
            $table->datetime('report_date');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('opd_patient_department_id')->references('id')->on('opd_patient_departments')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opd_diagnoses');
    }
};
