<?php

use Database\Seeders\CategorySeeder;
use Database\Seeders\OfficeSeeder;
use Database\Seeders\SupplySeeder;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        (new CategorySeeder)->run();
        (new OfficeSeeder)->run();
        (new SupplySeeder)->run();
    }

    public function down(): void
    {
        //
    }
};
