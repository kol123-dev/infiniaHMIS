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
        Schema::table('bed_assigns', function (Blueprint $table) {
            $table->index(['created_at', 'assign_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bed_assigns', function (Blueprint $table) {
            $table->dropIndex('bed_assigns_created_at_assign_date_index');
        });
    }
};
