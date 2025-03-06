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
        Schema::create('insurances', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 160);
            $table->double('service_tax');
            $table->double('discount')->nullable();
            $table->text('remark')->nullable();
            $table->string('insurance_no');
            $table->string('insurance_code');
            $table->double('hospital_rate');
            $table->double('total');
            $table->boolean('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('insurances');
    }
};
