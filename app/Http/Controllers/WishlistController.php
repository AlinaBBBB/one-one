<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\WishlistItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['count']);
    }
    
    // Страница избранного
    public function index()
    {
        $user = Auth::user();
        $wishlistItems = WishlistItem::with(['product.images'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(12);
        
        return view('wishlist.index', [
            'wishlistItems' => $wishlistItems,
            'title' => 'Избранное'
        ]);
    }
    
    // Добавить в избранное (исправлено)
    public function store(Request $request)
    {
        $user = Auth::user();
        
        // Валидация входных данных
        $validated = $request->validate([
            'product_id' => 'required|integer|exists:products,id'
        ]);
        
        $productId = $validated['product_id'];
        
        // Проверяем, есть ли уже в избранном
        $exists = WishlistItem::where('user_id', $user->id)
                             ->where('product_id', $productId)
                             ->exists();
        
        if ($exists) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Товар уже в избранном'
                ]);
            }
            return redirect()->back()
                           ->with('info', 'Товар уже в избранном');
        }
        
        WishlistItem::create([
            'user_id' => $user->id,
            'product_id' => $productId
        ]);
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Товар добавлен в избранное',
                'count' => $user->wishlistItems()->count()
            ]);
        }
        
        return redirect()->back()
                       ->with('success', 'Товар добавлен в избранное');
    }
    
    // Удалить из избранного (исправлено)
    public function destroy(Request $request)
    {
        $user = Auth::user();
        
        // Валидация входных данных
        $validated = $request->validate([
            'product_id' => 'required|integer|exists:products,id'
        ]);
        
        $productId = $validated['product_id'];
        
        WishlistItem::where('user_id', $user->id)
                   ->where('product_id', $productId)
                   ->delete();
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Товар удален из избранного',
                'count' => $user->wishlistItems()->count()
            ]);
        }
        
        return redirect()->back()
                       ->with('success', 'Товар удален из избранного');
    }
    
    // Переключить избранное (AJAX) - улучшенная версия
    public function toggle(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Требуется авторизация',
                'auth_url' => route('login')
            ], 401);
        }
        
        $user = Auth::user();
        
        // Валидация с дополнительными проверками
        $validated = $request->validate([
            'product_id' => 'required|integer|exists:products,id'
        ]);
        
        $productId = $validated['product_id'];
        
        // Дополнительная проверка на случай, если валидация пропустит что-то
        if (!is_numeric($productId) || $productId <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Некорректный ID товара'
            ], 422);
        }
        
        $item = WishlistItem::where('user_id', $user->id)
                           ->where('product_id', $productId)
                           ->first();
        
        if ($item) {
            $item->delete();
            $inWishlist = false;
            $message = 'Товар удален из избранного';
        } else {
            WishlistItem::create([
                'user_id' => $user->id,
                'product_id' => (int) $productId // Явное преобразование к int
            ]);
            $inWishlist = true;
            $message = 'Товар добавлен в избранное';
        }
        
        return response()->json([
            'success' => true,
            'in_wishlist' => $inWishlist,
            'message' => $message,
            'count' => $user->wishlistItems()->count()
        ]);
    }
    
    // Получить количество избранного
    public function count()
    {
        if (!Auth::check()) {
            return response()->json(['count' => 0]);
        }
        
        $count = Auth::user()->wishlistItems()->count();
        
        return response()->json(['count' => $count]);
    }
    
    // Очистить всё избранное
    public function clear()
    {
        $user = Auth::user();
        
        WishlistItem::where('user_id', $user->id)->delete();
        
        return redirect()->route('wishlist.index')
                       ->with('success', 'Все товары удалены из избранного');
    }
    
    // Удалить элемент из избранного со страницы избранного
    public function remove(Request $request, $wishlistItemId)
    {
        $user = Auth::user();
        
        WishlistItem::where('user_id', $user->id)
                   ->where('id', $wishlistItemId)
                   ->delete();
        
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Товар удален из избранного',
                'count' => $user->wishlistItems()->count()
            ]);
        }
        
        return redirect()->back()
                       ->with('success', 'Товар удален из избранного');
    }
}