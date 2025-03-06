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
        Schema::table('blood_banks', function (Blueprint $table) {
            $table->index('remained_bags');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blood_banks', function (Blueprint $table) {
            $table->dropIndex('blood_banks_remained_bags_index');
        });
    }
};
