<?php

namespace App\Traits;

use Illuminate\Http\Request;
use App\Models\Category;

trait Filterable
{
    /**
     * Применить все фильтры к запросу
     */
    protected function applyFilters($query, Request $request)
    {
        // Категории
        if ($request->has('categories')) {
            $this->filterByCategories($query, $request->categories);
        }
        
        // Цена
        $this->filterByPrice($query, $request);
        
        // Цвета
        if ($request->has('colors')) {
            $this->filterByColors($query, $request->colors);
        }
        
        // Размеры
        if ($request->has('sizes')) {
            $this->filterBySizes($query, $request->sizes);
        }
        
        // Наличие
        if ($request->filled('availability')) {
            $query->available($request->availability);
        }
        
        // Рейтинг
        if ($request->filled('rating')) {
            $query->minRating($request->rating);
        }
        
        // Новинки
        if ($request->boolean('is_new')) {
            $query->where('is_new', true);
        }
        
        // Популярные
        if ($request->boolean('is_popular')) {
            $query->where('is_popular', true);
        }
        
        // Скидки
        if ($request->boolean('is_on_sale') || $request->filled('discount_min')) {
            $query->onSale($request->get('discount_min'));
        }
        
        return $query;
    }
    
    /**
     * Применить сортировку
     */
    protected function applySorting($query, $sort)
    {
        switch ($sort) {
            case 'price_asc':
                return $query->orderBy('price', 'asc');
            case 'price_desc':
                return $query->orderBy('price', 'desc');
            case 'rating_desc':
                return $query->orderBy('avg_rating', 'desc')->orderBy('created_at', 'desc');
            case 'popular':
                return $query->orderBy('is_popular', 'desc')->orderBy('created_at', 'desc');
            case 'discount_desc':
                return $query->orderByRaw('discount DESC, (old_price - price) / old_price DESC');
            case 'newest':
            default:
                return $query->orderBy('created_at', 'desc');
        }
    }
    
    /**
     * Фильтр по категориям
     */
    private function filterByCategories($query, $categories)
    {
        $categorySlugs = explode(',', $categories);
        $categoryIds = Category::whereIn('slug', $categorySlugs)->pluck('id');
        
        if ($categoryIds->isNotEmpty()) {
            $query->whereIn('category_id', $categoryIds);
        }
    }
    
    /**
     * Фильтр по цене
     */
    private function filterByPrice($query, Request $request)
    {
        if ($request->filled('price_min')) {
            $query->where('price', '>=', (float)$request->price_min);
        }
        
        if ($request->filled('price_max')) {
            $query->where('price', '<=', (float)$request->price_max);
        }
    }
    
    /**
     * Фильтр по цветам
     */
    private function filterByColors($query, $colors)
    {
        $colorIds = array_map('intval', explode(',', $colors));
        $query->withColors($colorIds);
    }
    
    /**
     * Фильтр по размерам
     */
    private function filterBySizes($query, $sizes)
    {
        $sizeNames = explode(',', $sizes);
        $query->withSizes($sizeNames);
    }
}