<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    // --- 1. DAILY SALES REPORT ---
    public function reports(Request $request)
    {
        $selectedDate = $request->input('date', Carbon::today()->toDateString());

        // Orders on selected date
        $orders = Order::whereDate('created_at', $selectedDate)
            ->where('status', 'completed')
            ->get();

        $totalRevenue = $orders->sum('total');
        $totalOrders = $orders->count();
        $totalDiscount = $orders->sum('discount');

        // Payment breakdown (Cash, UPI, Card)
        $cashSales = $orders->where('payment_method', 'cash')->sum('total');
        $upiSales = $orders->where('payment_method', 'upi')->sum('total');
        $cardSales = $orders->where('payment_method', 'card')->sum('total');

        // Top 5 selling items on that date
        $topItems = OrderItem::whereHas('order', function ($query) use ($selectedDate) {
                $query->whereDate('created_at', $selectedDate)->where('status', 'completed');
            })
            ->select('product_name', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(total_price) as total_amount'))
            ->groupBy('product_name')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        return view('admin.reports', compact(
            'selectedDate',
            'totalRevenue',
            'totalOrders',
            'totalDiscount',
            'cashSales',
            'upiSales',
            'cardSales',
            'topItems',
            'orders'
        ));
    }

    // --- 2. ADMIN MENU MANAGEMENT ---
    public function products()
    {
        $categories = Category::with('products')->get();
        return view('admin.products', compact('categories'));
    }

    // Add new product
    public function storeProduct(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:1',
            'stock_quantity' => 'nullable|integer|min:0'
        ]);

        Product::create([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'price' => $validated['price'],
            'stock_quantity' => $validated['stock_quantity'] ?? 50,
            'is_available' => true,
        ]);

        return back()->with('success', 'Product added successfully!');
    }

    // Toggle Available / Out of Stock
    public function toggleProduct(Product $product)
    {
        $product->update([
            'is_available' => !$product->is_available,
        ]);

        return back()->with('success', 'Product status updated!');
    }

    // Update Price or Name
    public function updateProduct(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:1',
            'category_id' => 'required|exists:categories,id',
        ]);

        $product->update($validated);

        return back()->with('success', 'Product updated successfully!');
    }

        // 1. Force Admin re-login before delete
    public function promptDeleteSales(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login', ['action' => 'delete_sales']);
    }

    // 2. Permanently delete all sales from PostgreSQL
    public function deleteAllSales()
    {
        DB::transaction(function () {
            // Delete all order items & orders and reset IDs
            DB::statement('TRUNCATE TABLE order_items, orders RESTART IDENTITY CASCADE;');
        });

        return redirect()->route('admin.reports')->with('success', 'All sales reports and order history have been permanently deleted from the database.');
    }
}
  