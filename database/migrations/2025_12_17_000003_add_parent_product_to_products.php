// database/migrations/2025_12_17_000003_add_parent_product_to_products.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            
            if (!Schema::hasColumn('products', 'parent_product_id')) {
                $table->unsignedBigInteger('parent_product_id')->nullable()->after('id');
                $table->boolean('is_variant')->default(false)->after('parent_product_id');
                $table->unsignedBigInteger('main_color_id')->nullable()->after('is_variant');
                
                // Добавляем индексы
                $table->index('parent_product_id');
                $table->index('main_color_id');
                
                // Внешние ключи
                $table->foreign('parent_product_id')->references('id')->on('products')->onDelete('cascade');
                $table->foreign('main_color_id')->references('id')->on('colors')->onDelete('set null');
            }
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['parent_product_id']);
            $table->dropForeign(['main_color_id']);
            $table->dropColumn(['parent_product_id', 'is_variant', 'main_color_id']);
        });
    }
};