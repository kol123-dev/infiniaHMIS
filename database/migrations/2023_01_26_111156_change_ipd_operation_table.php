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
        Schema::table('ipd_operation', function (Blueprint $table) {
            $table->string('assistant_consultant_1')->nullable()->change();
            $table->string('assistant_consultant_2')->nullable()->change();
            $table->string('anesthetist')->nullable()->change();
            $table->string('anesthesia_type')->nullable()->change();
            $table->string('ot_technician')->nullable()->change();
            $table->string('ot_assistant')->nullable()->change();
            $table->string('remark')->nullable()->change();
            $table->string('result')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ipd_operation', function (Blueprint $table) {

        });
    }
};
