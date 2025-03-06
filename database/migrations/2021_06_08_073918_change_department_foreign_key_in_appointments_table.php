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
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign('appointments_department_id_foreign');
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->unsignedBigInteger('department_id')->change();
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->foreign('department_id')->references('id')->on('doctor_departments')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            //
        });
    }
};
