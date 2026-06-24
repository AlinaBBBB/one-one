<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            
            if (!Schema::hasColumn('products', 'old_price')) {
                $table->decimal('old_price', 10, 2)->nullable()->after('price');
            }
            
            if (!Schema::hasColumn('products', 'sku')) {
                $table->string('sku', 100)->nullable()->unique()->after('stock');
            }
            
            if (!Schema::hasColumn('products', 'product_type')) {
                $table->string('product_type', 50)->nullable()->after('category_id');
            }
            
            // Обновляем поля для одежды (можно удалить ненужные для фигурок)
            if (Schema::hasColumn('products', 'height')) {
                // Меняем значение по умолчанию (было 20)
                $table->integer('height')->default(0)->change();
            }
            
            // Меняем material по умолчанию с resin на cotton
            if (Schema::hasColumn('products', 'material')) {
                $table->string('material')->default('cotton')->change();
            }
            
            // Добавляем недостающие флаги
            if (!Schema::hasColumn('products', 'is_bestseller')) {
                $table->boolean('is_bestseller')->default(false)->after('is_popular');
            }
            
            if (!Schema::hasColumn('products', 'is_on_sale')) {
                $table->boolean('is_on_sale')->default(false)->after('is_bestseller');
            }
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            // Убираем добавленные поля (опционально)
            $columnsToDrop = ['old_price', 'sku', 'product_type', 'is_bestseller', 'is_on_sale'];
            
            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('products', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};