<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('offices', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('group');
            $table->decimal('allocation', 10, 2)->default(0);
            $table->string('prepared_by')->nullable();
            $table->string('prepared_by_designation')->nullable();
            $table->string('reviewed_by')->nullable();
            $table->string('reviewed_by_designation')->nullable();
            $table->string('approved_by')->nullable();
            $table->string('approved_by_designation')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offices');
    }
};
