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
        Schema::create('ipd_operation', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('operation_category_id');
            $table->unsignedBigInteger('operation_id');
            $table->unsignedInteger('ipd_patient_department_id');
            $table->date('operation_date');
            $table->unsignedBigInteger('doctor_id');
            $table->string('assistant_consultant_1');
            $table->string('assistant_consultant_2');
            $table->string('anesthetist');
            $table->string('anesthesia_type');
            $table->string('ot_technician');
            $table->string('ot_assistant');
            $table->string('remark');
            $table->string('result');
            $table->timestamps();

            $table->foreign('operation_category_id')->references('id')->on('operation_categories')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('doctor_id')->references('id')->on('doctors')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('operation_id')->references('id')->on('operations')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('ipd_patient_department_id')->references('id')->on('ipd_patient_departments')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ipd_operation');
    }
};
