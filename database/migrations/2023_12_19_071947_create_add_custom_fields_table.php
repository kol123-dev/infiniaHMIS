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
        Schema::create('add_custom_fields', function (Blueprint $table) {
            $table->id();
            $table->string('module_name');
            $table->string('field_type');
            $table->string('field_name');
            $table->boolean('is_required');
            $table->text('values')->nullable();
            $table->integer('grid')->default(12);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('add_custom_fields');
    }
};
