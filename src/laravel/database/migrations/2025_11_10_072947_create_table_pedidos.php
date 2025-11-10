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
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->integer('id_cliente')->unsigned();
            #$table->foreign('id_cliente')->references('id')->on('clientes')->onDelete('cascade');
            $table->integer('id_produto')->unsigned();
            #$table->foreign('id_produto')->references('id')->on('produtos')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_pedidos');
    }
};
