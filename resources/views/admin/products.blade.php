<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Menu - Madras Waffle House</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans min-h-screen">

    <!-- Top Bar -->
    <header class="bg-emerald-600 text-white px-6 py-4 flex justify-between items-center shadow-md">
        <h1 class="text-xl font-bold">⚙️ Menu & Product Management</h1>
        <div class="flex items-center gap-2.5">
            @auth
                <span class="text-xs bg-emerald-700 px-3 py-1.5 rounded-lg border border-emerald-500">
                    Logged in as: <strong>{{ auth()->user()->name }}</strong>
                </span>
            @endauth

            <a href="{{ route('admin.reports') }}" class="px-3.5 py-1.5 bg-white text-emerald-800 hover:bg-gray-100 rounded-lg text-xs font-bold transition shadow-sm">
                📊 Sales Reports
            </a>

            <a href="{{ route('pos.index') }}" class="px-3.5 py-1.5 bg-emerald-700 hover:bg-emerald-800 rounded-lg text-xs font-bold transition">
                ← Billing Counter
            </a>

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

    <main class="max-w-6xl mx-auto p-6 space-y-6">

        @if(session('success'))
            <div class="bg-emerald-100 border border-emerald-400 text-emerald-800 px-4 py-3 rounded-lg text-sm font-medium">
                {{ session('success') }}
            </div>
        @endif

        <!-- Add New Product Form -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <h2 class="text-lg font-bold text-gray-800 mb-4">+ Add New Menu Item</h2>
            <form action="{{ route('admin.products.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Category</label>
                    <select name="category_id" required class="w-full border rounded-lg px-3 py-2 text-sm bg-white">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Item Name</label>
                    <input type="text" name="name" placeholder="e.g. Nutella Waffle" required class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Price (₹)</label>
                    <input type="number" step="0.5" name="price" placeholder="e.g. 140" required class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Starting Stock</label>
                    <input type="number" name="stock_quantity" value="50" min="0" required class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 rounded-lg text-sm transition">
                        Add to Menu
                    </button>
                </div>
            </form>
        </div>

        <!-- Menu Table List -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-4 border-b">
                <h2 class="font-bold text-gray-800">Current Menu Items</h2>
            </div>
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                    <tr>
                        <th class="p-4">Item Name</th>
                        <th class="p-4">Category</th>
                        <th class="p-4">Price</th>
                        <th class="p-4">Stock</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($categories as $cat)
                        @foreach($cat->products as $product)
                        <tr class="hover:bg-gray-50">
                            <td class="p-4 font-semibold text-gray-800">{{ $product->name }}</td>
                            <td class="p-4 text-gray-500">{{ $cat->name }}</td>
                            <td class="p-4 font-bold text-emerald-600">₹{{ number_format($product->price, 2) }}</td>
                            <td class="p-4 font-bold {{ $product->stock_quantity <= 5 ? 'text-red-600' : 'text-gray-700' }}">
                                {{ $product->stock_quantity }} units
                            </td>
                            <td class="p-4">
                                @if($product->is_available)
                                    <span class="px-2.5 py-1 bg-green-100 text-green-800 rounded-full text-xs font-bold">In Stock</span>
                                @else
                                    <span class="px-2.5 py-1 bg-red-100 text-red-800 rounded-full text-xs font-bold">Out of Stock</span>
                                @endif
                            </td>
                            <td class="p-4 text-right">
                                <!-- Toggle Stock Button -->
                                <form action="{{ route('admin.products.toggle', $product->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-xs px-3 py-1.5 rounded-lg border font-medium {{ $product->is_available ? 'text-red-600 border-red-200 hover:bg-red-50' : 'text-green-600 border-green-200 hover:bg-green-50' }}">
                                        {{ $product->is_available ? 'Mark Out of Stock' : 'Mark In Stock' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>

    </main>

</body>
</html>
