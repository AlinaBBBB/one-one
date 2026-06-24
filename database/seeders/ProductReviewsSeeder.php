<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductReview;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductReviewsSeeder extends Seeder
{
    public function run()
    {
        // Очищаем таблицу перед заполнением
        DB::table('product_reviews')->truncate();

        // Создаем тестового пользователя, если его нет
        $user = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Тестовый Пользователь',
                'password' => bcrypt('password'),
                'role' => 0
            ]
        );

        // Получаем все товары
        $products = Product::all();

        $reviews = [
            [
                'rating' => 5,
                'comment' => 'Шикарное платье! Наконец-то у меня есть не только красивое, но и удобное вечернее платье. Единственное плохо тянется нижнее платье, лучше брать размер больше!'
            ],
            [
                'rating' => 4,
                'comment' => 'Красивое платье с оригинальным кремом и исполнением. Выглядит очень достойно для выхода на торжественное событие. На размер 44x46 гая полное соответствие выявленных параметров. Отказ "х" не хватило "свободы" на линии бедра, платье по фигуре.'
            ],
            [
                'rating' => 5,
                'comment' => 'Красивое платье, стройнит. На рост 172 отличная длина. Замена лепту на серебристый ремешок и будет еще лучше.'
            ],
            [
                'rating' => 4,
                'comment' => 'Мне очень понравилось.'
            ]
        ];

        foreach ($products as $product) {
            foreach ($reviews as $reviewData) {
                ProductReview::create([
                    'product_id' => $product->id,
                    'user_id' => $user->id,
                    'rating' => $reviewData['rating'],
                    'comment' => $reviewData['comment'],
                    'is_approved' => true
                ]);
            }
        }

        $this->command->info('Создано ' . ($products->count() * count($reviews)) . ' отзывов для товаров.');
    }
}