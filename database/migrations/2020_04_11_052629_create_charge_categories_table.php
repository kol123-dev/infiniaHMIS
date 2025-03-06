<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('charge_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 160);
            $table->text('description')->nullable();
            $table->integer('charge_type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('charge_categories');
    }
};
