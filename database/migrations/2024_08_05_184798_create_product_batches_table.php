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
        Schema::disableForeignKeyConstraints();

        Schema::create('product_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products');
            $table->string('batch_number');
            $table->date('expiration_date')->nullable();
            $table->decimal('price', 8, 2);
            $table->integer('quantity');
            //$table->foreignId('warehouse_id')->constrained('warehouse');
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['product_id', 'price', 'expiration_date'], 'product_unique_batch');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_batches');
    }
};