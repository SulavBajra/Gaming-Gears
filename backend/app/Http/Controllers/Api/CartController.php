<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CartRequest;
use App\Services\CartService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function __construct(private readonly CartService $cartService) {}

    public function store(CartRequest $request)
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $cart = DB::transaction(function () use ($request) {
            return $this->cartService->addToCart($request);
        });

        return response()->json(
            $cart->load('items.product', 'items.productVariant')
        );
    }

    public function index()
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $items = $this->cartService->getCart($user->id);

        return response()->json($items);
    }
}
