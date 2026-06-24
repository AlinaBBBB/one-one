<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Проверяем, существует ли таблица
        if (!Schema::hasTable('product_images')) {
            Schema::create('product_images', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('product_id');
                $table->string('image_path');
                $table->boolean('is_main')->default(false);
                $table->integer('sort_order')->default(0);
                $table->string('alt_text')->nullable();
                $table->timestamps();

                $table->foreign('product_id')
                ->references('id')->on('products')
                ->onDelete('cascade');
                      
                $table->index(['product_id', 'is_main']);
                $table->index('sort_order');
            });
        } else {
            // Если таблица уже есть, проверяем наличие необходимых полей
            Schema::table('product_images', function (Blueprint $table) {
                // Проверяем и добавляем отсутствующие поля
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
        Schema::dropIfExists('product_images');
    }
};