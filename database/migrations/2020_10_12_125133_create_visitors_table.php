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
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->integer('purpose');
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('id_card')->nullable();
            $table->string('no_of_person')->nullable();
            $table->date('date')->nullable();
            $table->time('in_time')->nullable();
            $table->time('out_time')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitors');
    }
};
