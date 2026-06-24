<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddParentProductToProducts extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('products', 'parent_product_id')) {
            Schema::table('products', function (Blueprint $table) {
                $table->unsignedBigInteger('parent_product_id')->nullable()->after('id');
            });
        }
        
        if (!Schema::hasColumn('products', 'is_variant')) {
            Schema::table('products', function (Blueprint $table) {
                $table->boolean('is_variant')->default(false)->after('parent_product_id');
            });
        }
        
        if (!Schema::hasColumn('products', 'main_color_id')) {
            Schema::table('products', function (Blueprint $table) {
                $table->unsignedBigInteger('main_color_id')->nullable()->after('is_variant');
            });
        }
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['parent_product_id', 'is_variant', 'main_color_id']);
        });
    }
}