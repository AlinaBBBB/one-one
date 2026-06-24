<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)
            ->withCount('items')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('users.profile', [
            'title' => 'Личный кабинет',
            'user' => $user,
            'orders' => $orders
        ]);
    }
}