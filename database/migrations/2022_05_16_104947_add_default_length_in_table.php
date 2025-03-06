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
        Schema::table('media', function (Blueprint $table) {
            $table->string('model_type', 160)->change();
        });
        Schema::table('model_has_roles', function (Blueprint $table) {
            $table->string('model_type', 160)->change();
        });
        Schema::table('model_has_permissions', function (Blueprint $table) {
            $table->string('model_type', 160)->unique()->change();
        });
        Schema::table('users', function (Blueprint $table) {
            $table->string('stripe_id', 100)->change();
            $table->string('card_brand', 100);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('table', function (Blueprint $table) {
            //
        });
    }
};
