<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CartRequest;
use App\Http\Requests\Api\CartUpdateRequest;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Models\CartItem;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function __construct(private readonly CartService $cartService) {}

    public function store(CartRequest $request): CartResource
    {
        $cart = DB::transaction(fn () => $this->cartService->addToCart($request));

        return new CartResource($cart->load('items.product', 'items.productVariant'));
    }

    public function index(): CartResource|JsonResponse
    {
        $cart = $this->cartService->getCart(Auth::id());

        if (! $cart instanceof Cart) {
            return response()->json(['data' => new Cart()]);
        }

        return new CartResource($cart->load('items.product', 'items.productVariant'));
    }

    public function destroy(Cart $cart)
    {
        Cart::destroy($cart->id);

        return response()->json(['message' => 'Cart deleted successfully']);
    }

    public function deleteItem(CartItem $cartItem)
    {
        CartItem::destroy($cartItem->id);

        return response()->json(['message' => 'Cart item deleted successfully']);
    }

    public function updateItem(CartUpdateRequest $request, CartItem $cartItem)
    {
        $cartItem->update([
            'quantity' => $request->quantity,
        ]);
        return response()->json([
            'message' => 'Cart item updated successfully'
        ]);
    }
}
