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
        if (! Schema::hasColumn('medicines', 'quantity')) {
            Schema::table('medicines', function (Blueprint $table) {
                $table->integer('quantity')->after('buying_price');
            });
            if (! Schema::hasColumn('medicines', 'available_quantity')) {
                Schema::table('medicines', function (Blueprint $table) {
                    $table->integer('available_quantity')->after('quantity');
                });

            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('medicines', 'quantity')) {
            Schema::table('medicines', function (Blueprint $table) {
                $table->dropColumn('quantity');
            });
        }
        if (Schema::hasColumn('medicines', 'available_quantity')) {
            Schema::table('medicines', function (Blueprint $table) {
                $table->dropColumn('available_quantity');
            });
        }
    }
};
