<?php
// public/test_db.php
require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;
use App\Models\ProductImage;

echo "<pre>";
echo "=== ТЕСТ БАЗЫ ДАННЫХ ONEONE ===\n\n";

// 1. Подключение
try {
    \DB::connection()->getPdo();
    echo "✓ Подключение к базе: УСПЕШНО\n";
} catch (Exception $e) {
    die("✗ Подключение к базе: ОШИБКА - " . $e->getMessage());
}

// 2. Таблицы
$tables = ['products', 'product_images', 'categories'];
foreach ($tables as $table) {
    try {
        \DB::select("SELECT 1 FROM {$table} LIMIT 1");
        echo "✓ Таблица '{$table}': СУЩЕСТВУЕТ\n";
    } catch (Exception $e) {
        echo "✗ Таблица '{$table}': ОТСУТСТВУЕТ\n";
    }
}

// 3. Товары
$productCount = Product::count();
echo "\nКоличество товаров: {$productCount}\n";

if ($productCount > 0) {
    $product = Product::with('images')->first();
    echo "\nПервый товар:\n";
    echo "  ID: {$product->id}\n";
    echo "  Название: {$product->title}\n";
    echo "  Тип: {$product->product_type}\n";
    echo "  Артикул: {$product->sku}\n";
    echo "  Изображений: " . $product->images->count() . "\n";
    
    foreach ($product->images as $image) {
        echo "  - Изображение: {$image->image_path} (Главное: " . ($image->is_main ? 'Да' : 'Нет') . ")\n";
    }
    
    echo "\nТестирование методов:\n";
    echo "  main_image_url: " . ($product->main_image_url ? '✓' : '✗') . "\n";
    echo "  all_images count: " . $product->all_images->count() . "\n";
} else {
    echo "\nТоваров нет. Добавьте тестовые данные через phpMyAdmin.\n";
}

echo "\n=== ТЕСТ ЗАВЕРШЕН ===\n";
echo "</pre>";