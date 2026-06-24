<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\Color;

class MergeProductVariants extends Command
{
    protected $signature = 'products:merge-variants';
    protected $description = 'Объединить товары одного типа с разными цветами';

    public function handle()
    {
        $this->info('Начинаем объединение товаров...');
        
        $products = Product::all();
        $groupedProducts = [];
        
        // Группируем товары по чистому названию
        foreach ($products as $product) {
            $cleanTitle = $this->getCleanTitle($product->title);
            
            if (!isset($groupedProducts[$cleanTitle])) {
                $groupedProducts[$cleanTitle] = [];
            }
            
            $groupedProducts[$cleanTitle][] = $product;
        }
        
        $mergedCount = 0;
        
        foreach ($groupedProducts as $cleanTitle => $productsInGroup) {
            if (count($productsInGroup) <= 1) {
                continue;
            }
            
            $this->info("Объединяем товары: {$cleanTitle}");
            
            // Сортируем товары
            usort($productsInGroup, function($a, $b) {
                return $this->getProductPriority($b) - $this->getProductPriority($a);
            });
            
            $mainProduct = array_shift($productsInGroup);
            
            // Обновляем основной товар
            $mainProduct->update([
                'title' => $cleanTitle,
                'is_variant' => false,
                'parent_product_id' => null,
            ]);
            
            // Определяем цвет основного товара
            $mainColor = $this->extractColorFromTitle($mainProduct->title);
            if ($mainColor) {
                $color = Color::where('name', 'like', "%{$mainColor}%")->first();
                if ($color) {
                    $mainProduct->update(['main_color_id' => $color->id]);
                }
            }
            
            // Обрабатываем вариации
            foreach ($productsInGroup as $variant) {
                $color = $this->extractColorFromTitle($variant->title);
                
                if ($color) {
                    $colorModel = Color::where('name', 'like', "%{$color}%")->first();
                    
                    if ($colorModel) {
                        $variant->update([
                            'parent_product_id' => $mainProduct->id,
                            'is_variant' => true,
                            'main_color_id' => $colorModel->id,
                            'title' => $cleanTitle . ' (' . $colorModel->name . ')',
                        ]);
                        
                        $mergedCount++;
                        $this->line("  → Вариация: {$colorModel->name}");
                    }
                }
            }
        }
        
        $this->info("\nГотово! Объединено {$mergedCount} вариаций.");
        
        return 0;
    }
    
    private function getCleanTitle($title)
    {
        $title = preg_replace('/\s*\([^)]*\)$/', '', $title);
        $title = preg_replace('/\s+(черный|белый|синий|красный|зеленый|желтый|розовый|коричневый|серый|бежевый|голубой|темно-синий)$/iu', '', $title);
        return trim($title);
    }
    
    private function getProductPriority(Product $product)
    {
        $title = $product->title;
        $priority = 0;
        
        if (strpos($title, '(Черный)') !== false) $priority += 10;
        if (strpos($title, '(Белый)') !== false) $priority += 9;
        if (!preg_match('/\([^)]+\)$/', $title)) $priority += 8;
        if (strpos($title, 'ONEONE') !== false) $priority += 5;
        
        return $priority;
    }
    
    private function extractColorFromTitle($title)
    {
        if (preg_match('/\(([^)]+)\)$/', $title, $matches)) {
            return trim($matches[1]);
        }
        
        $colors = ['Черный', 'Белый', 'Серый', 'Бежевый', 'Темно-синий', 'Коричневый', 'Розовый', 'Красный', 'Голубой'];
        
        foreach ($colors as $color) {
            if (preg_match('/\b' . preg_quote($color, '/') . '\b/iu', $title)) {
                return $color;
            }
        }
        
        return null;
    }
}