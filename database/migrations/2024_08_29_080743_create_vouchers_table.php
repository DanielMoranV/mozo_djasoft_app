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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id(); // Clave primaria
            $table->string('series', 4);
            $table->string('number', 8);
            $table->date('issue_date');
            $table->enum('type', ['boleta', 'factura', 'ticket', 'nota de credito', 'nota de debito']);
            $table->string('hash')->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('status')->default('pending');
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
        Schema::dropIfExists('vouchers');
        Schema::enableForeignKeyConstraints();
    }
};