<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display the shopping cart view.
     */
    public function index()
    {
        return view('cart.index', [
            'cart' => session('cart', [])
        ]);
    }

    /**
     * Add a product to the cart.
     */
    public function add(Request $request, Product $product)
    {
        abort_unless($product->active, 404);

        $cart = session('cart', []);
        $quantityToAdd = max(1, (int) $request->input('quantity', 1));
        $currentQuantity = $cart[$product->id]['quantity'] ?? 0;
        $newQuantity = min(10, $currentQuantity + $quantityToAdd);

        $cart[$product->id] = [
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'price' => (float) $product->price,
            'quantity' => $newQuantity,
            'platform' => $product->platform ?? 'PC',
            'region' => $product->region ?? 'Global',
            'edition' => $product->edition ?? 'Standard Edition',
            'image' => $product->image,
        ];

        session(['cart' => $cart]);

        return back()->with('success', "{$product->name} added to your cart.");
    }

    /**
     * Update quantity of a product in the cart.
     */
    public function update(Request $request, Product $product)
    {
        $cart = session('cart', []);

        if (isset($cart[$product->id])) {
            $action = $request->input('action'); // 'increase', 'decrease', or 'set'
            
            if ($action === 'increase') {
                $cart[$product->id]['quantity'] = min(10, $cart[$product->id]['quantity'] + 1);
            } elseif ($action === 'decrease') {
                $cart[$product->id]['quantity'] -= 1;
                if ($cart[$product->id]['quantity'] <= 0) {
                    unset($cart[$product->id]);
                    session(['cart' => $cart]);
                    return back()->with('success', "{$product->name} removed from your cart.");
                }
            } elseif ($request->has('quantity')) {
                $qty = (int) $request->input('quantity');
                if ($qty <= 0) {
                    unset($cart[$product->id]);
                    session(['cart' => $cart]);
                    return back()->with('success', "{$product->name} removed from your cart.");
                }
                $cart[$product->id]['quantity'] = min(10, $qty);
            }

            session(['cart' => $cart]);
        }

        return back()->with('success', 'Cart updated successfully.');
    }

    /**
     * Remove a product from the cart.
     */
    public function remove(Product $product)
    {
        $cart = session('cart', []);
        
        if (isset($cart[$product->id])) {
            $name = $cart[$product->id]['name'] ?? 'Product';
            unset($cart[$product->id]);
            session(['cart' => $cart]);
            return back()->with('success', "{$name} removed from your cart.");
        }

        return back();
    }

    /**
     * Clear all items from the cart.
     */
    public function clear()
    {
        session()->forget('cart');
        return back()->with('success', 'Your cart has been cleared.');
    }
}
