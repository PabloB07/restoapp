<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('firstname'); // Eliminar la columna
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('firstname')->nullable(); // Revertir el cambio si es necesario
        });
    }

};
