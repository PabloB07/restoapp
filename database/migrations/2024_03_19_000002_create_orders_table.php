<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('orders')) {
            Schema::create('orders', function (Blueprint $table) {
                $table->id();
                $table->foreignId('table_id')->nullable()->constrained()->onDelete('set null'); // Cambiado a 'set null'
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->enum('status', ['pending','in_progress', 'completed', 'cancelled'])->default('pending');
                $table->decimal('total', 10, 2)->default(0);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
