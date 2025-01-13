<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')
                  ->constrained()
                  ->onDelete('cascade');
            $table->decimal('percentage', 5, 2)->default(10.00); // Porcentaje de propina (ej: 10.00%)
            $table->decimal('amount', 10, 2); // Monto calculado de la propina
            $table->boolean('is_accepted')->default(false); // Si el cliente aceptó la propina
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tips');
    }
};
