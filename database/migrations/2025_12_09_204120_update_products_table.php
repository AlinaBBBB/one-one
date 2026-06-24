<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // Добавляем только отсутствующие поля
            
            if (!Schema::hasColumn('products', 'old_price')) {
                $table->decimal('old_price', 10, 2)->nullable()->after('price');
            }
            
            if (!Schema::hasColumn('products', 'is_bestseller')) {
                $table->boolean('is_bestseller')->default(false)->after('is_popular');
            }
            
            if (!Schema::hasColumn('products', 'is_on_sale')) {
                $table->boolean('is_on_sale')->default(false)->after('is_bestseller');
            }
            
            if (!Schema::hasColumn('products', 'sku')) {
                $table->string('sku', 100)->nullable()->unique()->after('stock');
            }
            
            if (!Schema::hasColumn('products', 'product_type')) {
                $table->string('product_type', 50)->nullable()->after('category_id');
            }
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            // Удаляем добавленные поля при откате
            $columnsToDrop = ['old_price', 'is_bestseller', 'is_on_sale', 'sku', 'product_type'];
            
            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('products', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};