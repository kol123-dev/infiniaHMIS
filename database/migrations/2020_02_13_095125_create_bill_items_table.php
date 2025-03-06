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
        Schema::create('bill_items', function (Blueprint $table) {
            $table->increments('id');
            $table->string('item_name');
            $table->unsignedInteger('bill_id');
            $table->integer('qty')->unsigned();
            $table->double('price', 8, 2);
            $table->double('amount', 8, 2);
            $table->timestamps();

            $table->foreign('bill_id')->references('id')->on('bills')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bill_items');
    }
};
