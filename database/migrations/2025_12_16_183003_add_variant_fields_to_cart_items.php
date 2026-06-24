<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
// database/migrations/xxxx_add_variant_fields_to_cart_items.php
public function up()
{
    Schema::table('cart_items', function (Blueprint $table) {
        $table->unsignedBigInteger('variant_id')->nullable()->after('product_id');
        $table->unsignedBigInteger('color_id')->nullable()->after('variant_id');
        $table->string('size')->nullable()->after('color_id');
        
        $table->foreign('variant_id')->references('id')->on('product_variants')->onDelete('cascade');
        $table->foreign('color_id')->references('id')->on('colors')->onDelete('cascade');
        
        
        $table->dropUnique('unique_cart_item');
        $table->unique(['user_id', 'product_id', 'variant_id', 'size'], 'unique_cart_item_with_variant');
    });
}

public function down()
{
    Schema::table('cart_items', function (Blueprint $table) {
        $table->dropForeign(['variant_id']);
        $table->dropForeign(['color_id']);
        $table->dropUnique('unique_cart_item_with_variant');
        $table->dropColumn(['variant_id', 'color_id', 'size']);
        
        // Восстанавливаем старую уникальность
        $table->unique(['user_id', 'product_id', 'size', 'color'], 'unique_cart_item');
    });
}
};
