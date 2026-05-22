<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('office_id')->constrained()->cascadeOnDelete();
            $table->foreignId('supply_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('quantity');
            $table->string('month_needed', 7); // YYYY-MM
            $table->timestamps();

            $table->unique(['office_id', 'supply_id', 'month_needed']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
