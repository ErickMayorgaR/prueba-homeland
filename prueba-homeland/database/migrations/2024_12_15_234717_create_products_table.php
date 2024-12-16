<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // Clave primaria
            $table->string('code')->unique(); // Código de producto único
            $table->string('name'); // Nombre del producto
            $table->integer('quantity'); // Cantidad
            $table->longText('photo')->nullable();             
            $table->decimal('price', 10, 2); // Precio con 2 decimales
            $table->date('entry_date'); // Fecha de ingreso
            $table->date('expiry_date'); // Fecha de vencimiento
            $table->timestamps(); // created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
