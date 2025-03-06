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
        Schema::table('radiology_tests', function (Blueprint $table) {
            $table->index('test_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('radiology_tests', function (Blueprint $table) {
            $table->dropIndex('radiology_tests_test_name_index');
        });
    }
};
