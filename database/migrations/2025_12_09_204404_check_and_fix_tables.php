<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // 1. Проверяем и добавляем поля в products
        Schema::table('products', function (Blueprint $table) {
            // Добавляем только отсутствующие поля
            $columns = ['old_price', 'is_bestseller', 'is_on_sale', 'product_type', 'sku'];
            
            foreach ($columns as $column) {
                if (!Schema::hasColumn('products', $column)) {
                    switch ($column) {
                        case 'old_price':
                            $table->decimal('old_price', 10, 2)->nullable()->after('price');
                            break;
                        case 'is_bestseller':
                            $table->boolean('is_bestseller')->default(false)->after('is_popular');
                            break;
                        case 'is_on_sale':
                            $table->boolean('is_on_sale')->default(false)->after('is_bestseller');
                            break;
                        case 'product_type':
                            $table->string('product_type', 50)->nullable()->after('category_id');
                            break;
                        case 'sku':
                            $table->string('sku', 100)->nullable()->unique()->after('stock');
                            break;
                    }
                }
            }
            
            // Обновляем значения по умолчанию
            if (Schema::hasColumn('products', 'material')) {
                // Обновляем существующие записи
                DB::table('products')->where('material', 'resin')->update(['material' => 'cotton']);
            }
            
            if (Schema::hasColumn('products', 'height')) {
                // Обновляем существующие записи
                DB::table('products')->where('height', 20)->update(['height' => 0]);
            }
        });

        // 2. Проверяем и добавляем поля в product_images
        if (Schema::hasTable('product_images')) {
            Schema::table('product_images', function (Blueprint $table) {
                if (!Schema::hasColumn('product_images', 'alt_text')) {
                    $table->string('alt_text')->nullable()->after('sort_order');
                }
                
                if (!Schema::hasColumn('product_images', 'sort_order')) {
                    $table->integer('sort_order')->default(0)->after('is_main');
                }
                
                if (!Schema::hasColumn('product_images', 'is_main')) {
                    $table->boolean('is_main')->default(false)->after('image_path');
                }
            });
        }
    }

    public function down()
    {
        // При откате ничего не делаем (опционально)
    }
};