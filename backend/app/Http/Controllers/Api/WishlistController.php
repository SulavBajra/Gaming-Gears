<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\WishlistResource;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WishlistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //TODO: remove these are only for tests
        $user = User::findOrFail(Auth::id());
        $cartItems = null;
        $variant = null;
        $product = null;
        $items = [];
        DB::transaction(function () use ($user, &$cartItems, &$variant, &$product) {
            $cartItems = $user->cart->items()
                ->with(['product.media', 'productVariant'])
                ->lockForUpdate()
                ->get();

            foreach ($cartItems as $item) {
                $product = $item->product;
                $variant = $item->productVariant;
            }
            // Do your order creation / updates here safely
        });

        return response()->json($product);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        Wishlist::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
        ]);

        return response()->json(['message' => 'Wishlist item added successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        $user = User::findOrFail(Auth::id());
        $wishlist = Wishlist::where('user_id', Auth::id())->with(['product.media','product.variants'])->get();
        return response()->json(WishlistResource::collection($wishlist));
        // return response()->json($wishlist);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function update(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $wishlist = Wishlist::where('user_id', Auth::id())->where('product_id', $request->product_id)->first();
        if ($wishlist) {
            $wishlist->delete();
            return response()->json(['message' => 'Product removed from wishlist']);
        }

        Wishlist::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
        ]);

        return response()->json(['message' => 'Wishlist item added successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Wishlist $wishlist)
    {
        $wishlist->delete();
        return response()->json(['message' => 'Wishlist item removed successfully']);
    }
}
