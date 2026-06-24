<?php

// корзина
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\CartHelper;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Отображение страницы корзины
     * Получает сводку корзины и передает данные в представление
     */
    public function index()
    {
        // Получаем сводную информацию о корзине через хелпер
        $cartSummary = CartHelper::getCartSummary();

        // Возвращаем представление с данными корзины
        return view('cart.index', [
            'cartItems' => $cartSummary['items'],        // Список товаров в корзине
            'subtotal' => $cartSummary['subtotal'],      // Промежуточная сумма
            'deliveryPrice' => $cartSummary['deliveryPrice'], // Стоимость доставки
            'total' => $cartSummary['total'],            // Общая сумма
            'totalQuantity' => $cartSummary['totalQuantity'] // Общее количество товаров
        ]);
    }

    /**
     * Добавление товара в корзину
     * Обрабатывает AJAX и обычные запросы
     */
    public function add(Request $request)
    {
        // Логирование для отладки
        \Log::info('=== CART ADD METHOD CALLED ===');
        \Log::info('Request data:', $request->all());
        \Log::info('User:', ['id' => auth()->id(), 'check' => auth()->check()]);
        
        // Проверяем авторизацию пользователя
        if (!Auth::check()) {
            \Log::warning('User not authenticated');
            
            // Для AJAX запросов возвращаем JSON
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Для добавления в корзину необходимо авторизоваться',
                    'redirect' => route('login')
                ], 401);
            }
            // Для обычных запросов - редирект на страницу логина
            return redirect()->route('login')->with('error', 'Для добавления в корзину необходимо авторизоваться');
        }

        // Валидация входных данных
        $request->validate([
            'product_id' => 'required|exists:products,id', // ID товара должен существовать в БД
            'quantity' => 'required|integer|min:1'         // Количество должно быть положительным числом
        ]);

        \Log::info('Validation passed');

        try {
            // Добавляем товар в корзину через хелпер
            \Log::info('Calling CartHelper::addToCart');
            CartHelper::addToCart($request->product_id, $request->quantity);
            
            // Получаем обновленную сводку корзины
            \Log::info('Getting cart summary');
            $cartSummary = CartHelper::getCartSummary();

            \Log::info('Cart added successfully', [
                'product_id' => $request->product_id,
                'new_cart_count' => $cartSummary['totalQuantity']
            ]);

            // Обработка AJAX запроса
            if ($request->ajax() || $request->wantsJson()) {
                \Log::info('Returning JSON response');
                return response()->json([
                    'success' => true,
                    'message' => 'Товар добавлен в корзину!',
                    'cart_count' => $cartSummary['totalQuantity'], // Общее количество товаров
                    'cart_total' => $cartSummary['total'],         // Общая сумма
                    'cart_items' => $cartSummary['items']          // Список товаров
                ]);
            }

            // Обычный HTTP ответ - редирект назад с сообщением
            \Log::info('Returning redirect response');
            return redirect()->back()->with('success', 'Товар добавлен в корзину!');

        } catch (\Exception $e) {
            // Обработка ошибок
            \Log::error('Cart add error: ' . $e->getMessage());
            
            // Для AJAX запросов
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ошибка при добавлении в корзину: ' . $e->getMessage()
                ], 500);
            }

            // Для обычных запросов
            return redirect()->back()->with('error', 'Ошибка при добавлении в корзину');
        }
    }

    /**
     * Обновление количества товара в корзине
     */
    public function update(Request $request)
{
    $request->validate([
        'item_id' => 'required',
        'quantity' => 'required|integer|min:1'
    ]);

    \Log::info('Cart update request:', $request->all());

    try {
        CartHelper::updateCartQuantity($request->item_id, $request->quantity);
        
        $cartSummary = CartHelper::getCartSummary();
        
        \Log::info('Cart updated successfully', [
            'item_id' => $request->item_id,
            'new_quantity' => $request->quantity
        ]);

        // Для AJAX запросов возвращаем JSON
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Количество обновлено',
                'subtotal' => $cartSummary['subtotal'],
                'total' => $cartSummary['total'],
                'totalQuantity' => $cartSummary['totalQuantity'],
                'cartCount' => $cartSummary['totalQuantity']
            ]);
        }

        return redirect()->back()->with('success', 'Количество обновлено');

    } catch (\Exception $e) {
        \Log::error('Cart update error: ' . $e->getMessage());
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при обновлении количества'
            ], 500);
        }
        
        return redirect()->back()->with('error', 'Ошибка при обновлении количества: ' . $e->getMessage());
    }
}

    /**
     * Удаление товара из корзины
     */
    
    public function remove(Request $request, $id)
    {
        \Log::info('Cart remove called', ['id' => $id, 'ajax' => $request->ajax()]);
        try {
            CartHelper::removeFromCart($id);
            $cartSummary = CartHelper::getCartSummary();
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Товар удален из корзины',
                    'subtotal' => $cartSummary['subtotal'],
                    'total' => $cartSummary['total'],
                    'totalQuantity' => $cartSummary['totalQuantity'],
                    'cartCount' => $cartSummary['totalQuantity']
                ]);
            }
            return redirect()->back()->with('success', 'Товар удален из корзины');
        } catch (\Exception $e) {
            \Log::error('Cart remove error: ' . $e->getMessage());
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ошибка при удалении товара'
                ], 500);
            }
            return redirect()->back()->with('error', 'Ошибка при удалении товара');
        }
    }

    /**
     * Очистка всей корзины
     */
    public function clear()
    {
        // Очищаем корзину через хелпер
        CartHelper::clearCart();

        // Редирект на страницу корзины с сообщением
        return redirect()->route('cart.index')->with('success', 'Корзина очищена');
    }

    /**
     * Получение количества товаров в корзине (для AJAX)
     */
    public function getCartCount()
    {
        // Получаем сводку корзины
        $cartSummary = CartHelper::getCartSummary();
        
        // Возвращаем JSON с количеством товаров
        return response()->json([
            'count' => $cartSummary['totalQuantity']
        ]);
    }

    /**
     * Страница оформления заказа
     */
    public function checkout()
    {
        // Получаем сводку корзины
        $cartSummary = CartHelper::getCartSummary();
        
        // Проверяем, что корзина не пуста
        if ($cartSummary['items']->count() === 0) {
            return redirect()->route('cart.index')->with('error', 'Корзина пуста');
        }

        // Возвращаем страницу оформления заказа с данными
        return view('cart.checkout', [
            'cartItems' => $cartSummary['items'],        // Товары в корзине
            'subtotal' => $cartSummary['subtotal'],      // Промежуточная сумма
            'deliveryPrice' => $cartSummary['deliveryPrice'], // Доставка
            'total' => $cartSummary['total'],            // Итоговая сумма
            'totalQuantity' => $cartSummary['totalQuantity'] // Количество
        ]);
    }
}