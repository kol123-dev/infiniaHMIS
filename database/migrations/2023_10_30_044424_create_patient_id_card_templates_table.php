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
        Schema::create('patient_id_card_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('color');
            $table->boolean('email')->default(1);
            $table->boolean('phone')->default(1);
            $table->boolean('dob')->default(1);
            $table->boolean('blood_group')->default(1);
            $table->boolean('address')->default(1);
            $table->boolean('patient_unique_id')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_id_card_templates');
    }
};
