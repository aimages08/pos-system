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
    DB::statement("ALTER TABLE sales MODIFY payment_method ENUM('cash','card','bank','credit','other') DEFAULT 'cash'");
}

public function down(): void
{
    DB::statement("ALTER TABLE sales MODIFY payment_method ENUM('cash','card','bank','other') DEFAULT 'cash'");
}
};
