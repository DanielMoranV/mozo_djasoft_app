<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('companies', function (Blueprint $table) {
            $table->id()->unsigned();
            $table->string('company_name')->unique();
            $table->string('ruc')->unique();
            $table->string('address')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->enum('status', ['active', 'inactive']);
            $table->string('logo_path')->nullable();

            // Emitir comprobantes electronicos Boletas Facturas
            $table->string('sol_user')->nullable();
            $table->string('sol_pass')->nullable();
            $table->string('cert_path')->nullable();

            // Emitir notas de credito / debito
            $table->string('client_id')->nullable();
            $table->string('client_secret')->nullable();

            $table->boolean('production')->default(false);

            $table->timestamps();
            $table->softDeletes();
        });
        Schema::enableForeignKeyConstraints();
    }

    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('companies');
        Schema::enableForeignKeyConstraints();
    }
}
