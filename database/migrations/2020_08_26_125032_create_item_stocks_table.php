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
        Schema::create('item_stocks', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('item_category_id');
            $table->unsignedInteger('item_id');
            $table->string('supplier_name')->nullable();
            $table->string('store_name')->nullable();
            $table->integer('quantity');
            $table->double('purchase_price');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('item_category_id')->references('id')->on('item_categories')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('item_id')->references('id')->on('items')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('item_stocks');
    }
};
