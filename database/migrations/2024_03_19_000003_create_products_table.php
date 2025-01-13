<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('products')) {
            Schema::create('products', function (Blueprint $table) {
                $table->id();
                $table->string('name')->default('');
                $table->text('description')->nullable();
                $table->decimal('price', 10, 2)->default(0);
                $table->enum('category', ['comida', 'bebestible', 'postre', 'verdura', 'fruta', 'jugo', 'barra']);
                $table->boolean('available')->default(true);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
