<?php

namespace App\Helpers;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Auth;

class CartHelper
{
    /**
     * Получить количество товаров в корзине
     */
    public static function getCartCount()
    {
        $count = 0;

        if (Auth::check()) {
            $count = CartItem::where('user_id', Auth::id())->sum('quantity');
        } else {
            $cart = session()->get('cart', []);
            $count = array_sum(array_column($cart, 'quantity'));
        }

        return $count;
    }

    /**
     * Получить все товары в корзине (с поддержкой вариаций)
     */
    public static function getCartItems()
    {
        $cartItems = collect();

        if (Auth::check()) {
            // Загружаем с вариациями и цветами
            $cartItems = CartItem::where('user_id', Auth::id())
                ->with(['product.images', 'variant.color', 'color'])
                ->get();
        } else {
            // Для неавторизованных пользователей (сессия)
            $cart = session()->get('cart', []);
            foreach ($cart as $key => $item) {
                // Ключ может быть в формате: product_id или product_id_colorId_size
                $parts = explode('_', $key);
                $productId = $parts[0];
                $colorId = isset($parts[1]) && is_numeric($parts[1]) ? $parts[1] : null;
                $size = isset($parts[2]) ? $parts[2] : null;
                
                $product = Product::with('images')->find($productId);
                if ($product) {
                    $cartItem = (object)[
                        'id' => $key,
                        'product' => $product,
                        'quantity' => $item['quantity'],
                        'color_id' => $colorId,
                        'size' => $size
                    ];
                    
                    // Если есть color_id, получаем цвет
                    if ($colorId) {
                        $color = \App\Models\Color::find($colorId);
                        $cartItem->color = $color;
                    }
                    
                    $cartItems->push($cartItem);
                }
            }
        }

        return $cartItems;
    }

    /**
     * Получить общую стоимость корзины (с учетом вариаций)
     */
    public static function getCartTotal()
    {
        $cartItems = self::getCartItems();
        return $cartItems->sum(function($item) {
            // Используем цену вариации, если есть
            if (isset($item->variant) && $item->variant->price) {
                return $item->variant->price * $item->quantity;
            }
            
            // Иначе цену товара
            if (isset($item->product) && $item->product->price) {
                return $item->product->price * $item->quantity;
            }
            
            return 0;
        });
    }


      
        public static function addToCart($productId, $quantity = 1, $colorId = null, $size = null)
        {
            if (Auth::check()) {
                $product = Product::find($productId);
                if (!$product) {
                    throw new \Exception('Товар не найден');
                }
                $query = CartItem::where('user_id', Auth::id())
                    ->where('product_id', $productId);
                
                if (!empty($size)) {
                    $query->where('size', $size);
                } else {
                    $query->whereNull('size');
                }
                if (!empty($colorId)) {
                    $query->where('color_id', $colorId);
                } else {
                    $query->whereNull('color_id');
                }
                $existingItem = $query->first();
                if ($existingItem) {
                    $existingItem->quantity += $quantity;
                    $existingItem->save();
                } else {
                    $data = [
                        'user_id' => Auth::id(),
                        'product_id' => $productId,
                        'quantity' => $quantity
                    ];
                    if (!empty($size)) {
                        $data['size'] = $size;
                    }
                    if (!empty($colorId)) {
                        $data['color_id'] = $colorId;
                    }
                    CartItem::create($data);
                }
            } else {
                $cart = session()->get('cart', []);    
                $cartKey = $productId;
                if (!empty($colorId)) {
                    $cartKey .= '_' . $colorId;
                }
                if (!empty($size)) {
                    $cartKey .= '_' . $size;
                }
                if (isset($cart[$cartKey])) {
                    $cart[$cartKey]['quantity'] += $quantity;
                } else {
                    $cart[$cartKey] = [
                        'product_id' => $productId,
                        'quantity' => $quantity,
                        'color_id' => $colorId,
                        'size' => $size,
                        'added_at' => now()
                    ];
                }
                session()->put('cart', $cart);
            }
        }

    /**
     * Удалить товар из корзины
     */
    public static function removeFromCart($itemId)
    {
        if (Auth::check()) {
            $cartItem = CartItem::where('user_id', Auth::id())->find($itemId);
            if ($cartItem) {
                // Возвращаем товар на склад
                self::restoreStock($cartItem);
                $cartItem->delete();
            }
        } else {
            $cart = session()->get('cart', []);
            if (isset($cart[$itemId])) {
                unset($cart[$itemId]);
                session()->put('cart', $cart);
            }
        }
    }

    /**
     * Обновить количество товара в корзине
     */
    public static function updateCartQuantity($itemId, $quantity)
    {
        if (Auth::check()) {
            $cartItem = CartItem::where('user_id', Auth::id())->find($itemId);
            if ($cartItem) {
                // Возвращаем старый сток
                self::restoreStock($cartItem);
                
                // Обновляем количество
                $cartItem->quantity = $quantity;
                $cartItem->save();
                
                // Уменьшаем новый сток
                self::decreaseStock($cartItem);
            }
        } else {
            $cart = session()->get('cart', []);
            if (isset($cart[$itemId])) {
                $cart[$itemId]['quantity'] = $quantity;
                session()->put('cart', $cart);
            }
        }
    }

    /**
     * Очистить корзину
     */
    public static function clearCart()
    {
        if (Auth::check()) {
            // Возвращаем все товары на склад
            $cartItems = CartItem::where('user_id', Auth::id())->get();
            foreach ($cartItems as $cartItem) {
                self::restoreStock($cartItem);
            }
            
            CartItem::where('user_id', Auth::id())->delete();
        } else {
            session()->forget('cart');
        }
    }

    /**
     * Получить сводку корзины (с поддержкой вариаций)
     */
    public static function getCartSummary()
    {
        $cartItems = self::getCartItems();
        $totalQuantity = $cartItems->sum('quantity');
        
        // Расчет суммы с учетом вариаций
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $price = 0;
            
            // Цена вариации
            if (isset($item->variant) && $item->variant->price) {
                $price = $item->variant->price;
            } 
            // Или цена товара
            elseif (isset($item->product) && $item->product->price) {
                $price = $item->product->price;
            }
            
            $subtotal += $price * $item->quantity;
        }
        
        $deliveryPrice = $subtotal > 5000 ? 0 : 500;
        $total = $subtotal + $deliveryPrice;

        return [
            'items' => $cartItems,
            'totalQuantity' => $totalQuantity,
            'subtotal' => $subtotal,
            'deliveryPrice' => $deliveryPrice,
            'total' => $total
        ];
    }
    
    /**
     * Вспомогательный метод: уменьшить сток
     */
    private static function decreaseStock($cartItem)
    {
        if ($cartItem->variant_id) {
            // Для вариации
            $variant = ProductVariant::find($cartItem->variant_id);
            if ($variant) {
                $variant->decrement('stock', $cartItem->quantity);
            }
        } else {
            // Для обычного товара
            Product::where('id', $cartItem->product_id)
                ->decrement('stock', $cartItem->quantity);
        }
    }
    
    /**
     * Вспомогательный метод: вернуть товар на склад
     */
    private static function restoreStock($cartItem)
    {
        if ($cartItem->variant_id) {
            // Для вариации
            $variant = ProductVariant::find($cartItem->variant_id);
            if ($variant) {
                $variant->increment('stock', $cartItem->quantity);
            }
        } else {
            // Для обычного товара
            Product::where('id', $cartItem->product_id)
                ->increment('stock', $cartItem->quantity);
        }
    }
    
    /**
     * Проверить, достаточно ли товара в наличии (с поддержкой вариаций)
     */
    public static function checkStock($productId, $quantity, $colorId = null, $size = null)
    {
        if ($colorId) {
            $variant = ProductVariant::where('product_id', $productId)
                ->where('color_id', $colorId)
                ->where('is_active', true)
                ->first();
            
            if (!$variant) {
                return false;
            }
            
            return $variant->stock >= $quantity;
        } else {
            $product = Product::find($productId);
            return $product && $product->stock >= $quantity;
        }
    }
    
    /**
     * Объединение корзины из сессии с корзиной пользователя
     */
    public static function mergeSessionCart($userId)
    {
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return;
        }
        
        foreach ($cart as $key => $sessionItem) {
            $parts = explode('_', $key);
            $productId = $parts[0];
            $colorId = isset($parts[1]) && is_numeric($parts[1]) ? $parts[1] : null;
            $size = isset($parts[2]) ? $parts[2] : null;
            
            // Добавляем в БД
            $existingItem = CartItem::where('user_id', $userId)
                ->where('product_id', $productId)
                ->when($colorId, function($query) use ($colorId) {
                    return $query->where('color_id', $colorId);
                })
                ->when($size, function($query) use ($size) {
                    return $query->where('size', $size);
                })
                ->first();
            
            if ($existingItem) {
                $existingItem->quantity += $sessionItem['quantity'];
                $existingItem->save();
            } else {
                CartItem::create([
                    'user_id' => $userId,
                    'product_id' => $productId,
                    'color_id' => $colorId,
                    'size' => $size,
                    'quantity' => $sessionItem['quantity']
                ]);
            }
        }
        
        // Очищаем сессию
        session()->forget('cart');
    }
}