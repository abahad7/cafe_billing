<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PosController extends Controller
{
    public function index()
    {
        $categories = Category::with('products')->get();
        return view('pos.index', compact('categories'));
    }

    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.name' => 'required|string',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|in:cash,upi,card',
             'customer_phone' => 'nullable|string|max:15',
            'discount' => 'nullable|numeric|min:0',
        ]);

        return DB::transaction(function () use ($validated) {
            $subtotal = collect($validated['items'])->sum(function ($item) {
                return $item['price'] * $item['quantity'];
            });

            $discount = $validated['discount'] ?? 0;
            $total = max(0, $subtotal - $discount);

           $order = Order::create([
                'order_number' => 'ORD-' . strtoupper(Str::random(6)),
                'customer_phone' => $validated['customer_phone'] ?? null,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'total' => $total,
                'payment_method' => $validated['payment_method'],
                'status' => 'completed',
            ]);

            // 2. Create Items & Deduct Stock
            foreach ($validated['items'] as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'product_name' => $item['name'],
                    'unit_price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'total_price' => $item['price'] * $item['quantity'],
                ]);
                // Reduce inventory stock in PostgreSQL
                $product = Product::find($item['product_id']);
                if ($product) {
                    $product->decrement('stock_quantity', $item['quantity']);
                    if ($product->stock_quantity <= 0) {
                        $product->update(['is_available' => false, 'stock_quantity' => 0]);
                    }
                }
            }
          
    
            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'redirect' => route('pos.receipt', $order->id),
            ]);
        });
    }

    public function receipt(Order $order)
    {
        $order->load('items');
        return view('pos.receipt', compact('order'));
    }
}