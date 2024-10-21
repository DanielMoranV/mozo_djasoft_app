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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique();
            $table->foreignId('company_id')->constrained('companies');
            $table->foreignId('provider_id')->constrained('providers');
            $table->date('expected_delivery')->nullable();
            $table->string('status')->default('Pendiente');
            $table->decimal('amount', 8, 2);
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('warehouse_id')->constrained('warehouses');
            $table->string('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};