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
        Schema::table('ipd_charges', function (Blueprint $table) {
            $table->double('standard_charge')->nullable()->change();
            $table->double('applied_charge')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ipd_charges', function (Blueprint $table) {
            //
        });
    }
};
