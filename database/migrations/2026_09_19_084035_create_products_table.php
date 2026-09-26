<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
                $table->id();
                $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
                $table->string('name');
                $table->string('sku')->nullable()->unique();
                $table->string('barcode')->nullable()->unique();
                $table->text('description')->nullable();
                $table->decimal('cost_price', 10, 2)->nullable()->default(0);
                $table->decimal('selling_price', 10, 2)->nullable()->default(0);
                $table->decimal('wholesale_price', 10, 2)->nullable()->default(0);
                $table->integer('stock')->nullable()->default(0);
                $table->integer('minimum_stock')->nullable()->default(0);
                $table->string('unit')->nullable()->default('pcs');
                $table->decimal('tax', 5, 2)->nullable()->default(0);
                $table->decimal('discount', 5, 2)->nullable()->default(0);
                $table->string('image')->nullable();
                $table->enum('status', ['active', 'disabled'])->default('active');
                $table->timestamps();
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};