<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Sales Report - Fresh Cafe</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
         .footer-gradient {
            background: linear-gradient(135deg, #059669 0%, #2563eb 50%, #38bdf8 100%);
        }
    </style>
   
</head>
<body class="bg-gray-100 font-sans min-h-screen flex flex-col justify-between">
    
    <!-- Clean Top Bar Header -->
<!-- Top Bar Header (Clean - No Delete Button) -->
<header class="bg-emerald-600 text-white px-6 py-4 flex justify-between items-center shadow-md">
    
    <!-- Title -->
    <div class="flex items-center gap-4">
        <h1 class="text-xl font-bold">📊 Sales Reports & Analytics</h1>
    </div>
    
    <!-- Navigation Buttons -->
    <div class="flex items-center gap-2.5">
        
        @auth
            <span class="text-xs bg-emerald-700 px-3 py-1.5 rounded-lg border border-emerald-500">
                Logged in as: <strong>{{ auth()->user()->name }}</strong>
            </span>
        @endauth
        <!-- Manage Menu -->
        <a href="{{ route('admin.products') }}" class="px-3.5 py-1.5 bg-white text-emerald-800 hover:bg-gray-100 rounded-lg text-xs font-bold transition shadow-sm">
            ⚙️ Manage Menu
        </a>
        <!-- Back to Billing -->
        <a href="{{ route('pos.index') }}" class="px-3.5 py-1.5 bg-emerald-700 hover:bg-emerald-800 rounded-lg text-xs font-bold transition">
            ← Billing Counter
        </a>
        <!-- Logout -->
        @auth
        <form action="{{ route('logout') }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="px-3.5 py-1.5 bg-red-500 hover:bg-red-600 text-white rounded-lg text-xs font-bold transition">
                Logout
            </button>
        </form>
        @endauth
    </div>
</header>

    <main class="max-w-6xl w-full mx-auto p-6 space-y-6 flex-1">

       @if(session('success'))
            <div class="bg-emerald-100 border border-emerald-400 text-emerald-800 px-4 py-3 rounded-lg text-sm font-medium">
                {{ session('success') }}
            </div>
        @endif

        <!-- Date Picker Filter -->
        <div class="bg-white p-4 rounded-xl shadow-sm flex justify-between items-center">
            <h2 class="text-lg font-bold text-gray-800">
                Sales for: <span class="text-emerald-600">{{ \Carbon\Carbon::parse($selectedDate)->format('D, d M Y') }}</span>
            </h2>
            <form method="GET" action="{{ route('admin.reports') }}" class="flex items-center gap-2">
                <input type="date" name="date" value="{{ $selectedDate }}" class="border rounded-lg px-3 py-1.5 text-sm">
                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-1.5 rounded-lg text-sm font-semibold transition">
                    Filter Date
                </button>
            </form>
        </div>

        <!-- Metric Stat Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm">
                <p class="text-xs uppercase font-bold text-gray-400">Total Revenue</p>
                <p class="text-3xl font-extrabold text-emerald-600 mt-1">₹{{ number_format($totalRevenue, 2) }}</p>
            </div>
            <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm">
                <p class="text-xs uppercase font-bold text-gray-400">Orders Completed</p>
                <p class="text-3xl font-extrabold text-gray-800 mt-1">{{ $totalOrders }}</p>
            </div>
            <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm">
                <p class="text-xs uppercase font-bold text-gray-400">Total Discounts</p>
                <p class="text-3xl font-extrabold text-orange-500 mt-1">₹{{ number_format($totalDiscount, 2) }}</p>
            </div>
            <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm">
                <p class="text-xs uppercase font-bold text-gray-400">Average Order Value</p>
                <p class="text-3xl font-extrabold text-blue-600 mt-1">
                    ₹{{ $totalOrders > 0 ? number_format($totalRevenue / $totalOrders, 2) : '0.00' }}
                </p>
            </div>
        </div>

        <!-- Payment Breakdown & Top Selling Items -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Payment Breakdown -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-800 text-base mb-4">Payment Methods Breakdown</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                        <span class="font-medium text-gray-700">💵 Cash Collections</span>
                        <span class="font-bold text-gray-900">₹{{ number_format($cashSales, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-emerald-50 rounded-lg">
                        <span class="font-medium text-emerald-900">📱 UPI / QR Payments</span>
                        <span class="font-bold text-emerald-700">₹{{ number_format($upiSales, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-blue-50 rounded-lg">
                        <span class="font-medium text-blue-900">💳 Card Swipes</span>
                        <span class="font-bold text-blue-700">₹{{ number_format($cardSales, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Top 5 Selling Items -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-800 text-base mb-4">🔥 Top Selling Items Today</h3>
                @if($topItems->isEmpty())
                    <p class="text-gray-400 text-sm">No items sold on this date yet.</p>
                @else
                    <div class="space-y-2">
                        @foreach($topItems as $item)
                        <div class="flex justify-between items-center border-b pb-2">
                            <span class="font-medium text-gray-800 text-sm">{{ $item->product_name }}</span>
                            <div class="text-right">
                                <span class="bg-emerald-100 text-emerald-800 text-xs px-2 py-0.5 rounded-full font-bold">{{ $item->total_qty }} sold</span>
                                <span class="text-xs text-gray-500 ml-2">₹{{ number_format($item->total_amount, 2) }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>

    </main>
     <footer class="footer-gradient text-white py-4 px-8 mt-12 shadow-2xl flex flex-col md:flex-row justify-between items-center gap-4">
        <div class="text-sm font-medium opacity-95">
            🧇 <strong>Madras Waffle House</strong> • Management & Analytics Portal
        </div>
        <!-- Delete All Sales Reports Button in Footer -->
        <div>
            <a href="{{ route('admin.reports.prompt-delete') }}" 
               onclick="return confirm('To delete sales reports, you must re-verify your Admin credentials. Proceed to Sign In?')"
               class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-xs font-extrabold tracking-wide uppercase transition shadow-md flex items-center gap-2 border border-red-400 hover:shadow-lg">
                🗑️ Delete All Sales Data
            </a>
        </div>
    </footer>
    <!-- Hidden form to execute sales deletion -->
    <form id="execute-delete-sales-form" action="{{ route('admin.reports.delete-all') }}" method="POST" style="display: none;">
        @csrf
    </form>
    <!-- Alert Box after login asking: Sure to Delete all Sales Reports & Analytics -->
    @if(request('confirm_delete') == 1)
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                if (confirm("⚠️ Sure to Delete all Sales Reports & Analytics? \n\nThis will permanently remove all orders and sales records from the database.")) {
                    document.getElementById('execute-delete-sales-form').submit();
                } else {
                    // Remove parameter from URL if cancelled
                    window.history.replaceState({}, document.title, "{{ route('admin.reports') }}");
                }
            }, 300);
        });
    </script>
    @endif
</body>
</html>
</body>
</html>