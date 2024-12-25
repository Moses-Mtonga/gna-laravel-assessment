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
        Schema::create('farm_support_products', function (Blueprint $table) {
            $table->unsignedBigInteger('farm_support_id');
            $table->unsignedBigInteger('product_id');
            $table->foreign('farm_support_id')->references('id')->on('farm_supports')->onDelete('cascade');
            $table->foreign('supported_product_id')->references('id')->on('supported_products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farm_support_products');
    }
};
