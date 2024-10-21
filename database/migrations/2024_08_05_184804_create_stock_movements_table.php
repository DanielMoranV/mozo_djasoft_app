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

        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string('comment')->nullable();
            $table->foreignId('category_movements_id')->constrained('category_movements');
            $table->foreignId('provider_id')->nullable()->constrained('providers');
            $table->foreignId('warehouse_id')->constrained('warehouses');
            $table->foreignId('voucher_id')->unique()->constrained('vouchers')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('stock_movements');
        Schema::enableForeignKeyConstraints();
    }
};
