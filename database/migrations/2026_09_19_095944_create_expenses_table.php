<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->date('expense_date');
            $table->string('title');                  // e.g. Electricity Bill
            $table->string('category')->nullable();   // e.g. Utilities, Rent, Salary
            $table->decimal('amount', 12, 2)->default(0);
            $table->string('payment_method')->default('cash'); // cash, bank, card, other
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};