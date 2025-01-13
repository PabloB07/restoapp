<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashBoxesTable extends Migration
{
    public function up()
    {
        Schema::create('cash_boxes', function (Blueprint $table) {
            $table->id();
            $table->date('date')->unique(); // Fecha de la caja
            $table->decimal('total_income', 10, 2)->default(0); // Ingresos totales
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cash_boxes');
    }
}
