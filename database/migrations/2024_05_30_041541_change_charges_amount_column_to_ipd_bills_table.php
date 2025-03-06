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
        Schema::table('ipd_bills', function (Blueprint $table) {
            $table->double('total_charges')->change();
            $table->double('total_payments')->change();
            $table->double('gross_total')->change();
            $table->double('other_charges')->change();
            $table->double('net_payable_amount')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ipd_bills', function (Blueprint $table) {
            //
        });
    }
};
