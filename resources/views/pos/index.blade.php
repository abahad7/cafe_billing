<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Madras Waffle House - Billing</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100 h-screen overflow-hidden flex flex-col font-sans" x-data="posApp()">

    <!-- Top Bar with Centered "Madras Waffle House" & No Timestamp -->
    <header class="bg-emerald-600 text-white px-6 py-3 relative flex items-center justify-between shadow-md">
        
        <!-- Left: Logo -->
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-10 w-auto object-contain" onerror="this.style.display='none'">
        </div>

        <!-- Center: Madras Waffle House -->
        <div class="absolute left-1/2 transform -translate-x-1/2 text-center pointer-events-none">
            <h1 class="text-2xl font-black tracking-wider uppercase flex items-center gap-2 drop-shadow-sm">
                Madras Waffle House
            </h1>
        </div>

        <!-- Right: Admin Navigation Links -->
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.reports') }}" class="px-3.5 py-1.5 bg-emerald-700 hover:bg-emerald-800 rounded-lg text-xs font-bold transition shadow-sm">
                📊 Sales Report
            </a>
           
        </div>
    </header>

    <!-- Success Toast Notification -->
    <div x-show="showSuccessNotification" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="fixed top-16 right-6 z-50 bg-emerald-700 text-white px-5 py-3 rounded-xl shadow-2xl flex items-center gap-3 border border-emerald-400">
        <span class="text-2xl">✅</span>
        <div>
            <p class="font-bold text-sm">Bill Completed & Saved!</p>
            <p class="text-xs text-emerald-100">Ready for next customer.</p>
        </div>
    </div>

    <!-- Main Workspace -->
    <div class="flex-1 flex overflow-hidden">
        
        <!-- Left: Categories & Items (65% width) -->
        <div class="w-2/3 flex flex-col border-r border-gray-200 bg-white">
            <!-- Category Tabs -->
            <div class="flex gap-2 p-3 overflow-x-auto border-b bg-gray-50">
                <button 
                    @click="activeCategory = 'all'" 
                    :class="activeCategory === 'all' ? 'bg-emerald-600 text-white shadow' : 'bg-white text-gray-700 hover:bg-gray-100'"
                    class="px-4 py-2 rounded-lg font-medium text-sm transition">
                    All Items
                </button>
                @foreach($categories as $category)
                    <button 
                        @click="activeCategory = '{{ $category->slug }}'" 
                        :class="activeCategory === '{{ $category->slug }}' ? 'bg-emerald-600 text-white shadow' : 'bg-white text-gray-700 hover:bg-gray-100'"
                        class="px-4 py-2 rounded-lg font-medium text-sm transition whitespace-nowrap">
                        {{ $category->name }}
                    </button>
                @endforeach
            </div>

            <!-- Product Grid -->
            <div class="flex-1 overflow-y-auto p-4 grid grid-cols-3 gap-4">
                @foreach($categories as $category)
                    @foreach($category->products as $product)
                        <div 
                            x-show="activeCategory === 'all' || activeCategory === '{{ $category->slug }}'"
                            @click="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }})"
                            class="bg-white border-2 border-gray-100 hover:border-emerald-500 rounded-xl p-4 cursor-pointer shadow-sm hover:shadow-md transition flex flex-col justify-between h-28">
                            <h3 class="font-semibold text-gray-800 text-base leading-tight">{{ $product->name }}</h3>
                            <div class="flex justify-between items-center mt-2">
    <span class="text-xs text-gray-400 uppercase tracking-wider">
        {{ $product->stock_quantity }} left
    </span>
    <span class="font-bold text-emerald-600 text-lg">₹{{ number_format($product->price, 2) }}</span>
</div>
                        </div>
                    @endforeach
                @endforeach
            </div>
        </div>

        <!-- Right: Current Bill / Cart (35% width) -->
        <div class="w-1/3 flex flex-col bg-gray-50">
            <!-- Cart Header -->
            <div class="p-4 border-b bg-white flex justify-between items-center shadow-sm">
                <h2 class="font-bold text-gray-800 text-lg">Current Bill</h2>
                <button @click="resetCart()" x-show="cart.length > 0" class="text-xs text-red-500 hover:underline font-semibold">Clear All</button>
            </div>

            <!-- Cart Items List -->
            <div class="flex-1 overflow-y-auto p-4 space-y-3">
                <template x-if="cart.length === 0">
                    <div class="text-center text-gray-400 mt-16">
                        <p class="text-4xl mb-2">🛒</p>
                        <p class="font-medium">No items in current bill</p>
                        <p class="text-xs">Click items on the left to add</p>
                    </div>
                </template>

                <template x-for="(item, index) in cart" :key="item.product_id">
                    <div class="bg-white p-3 rounded-lg shadow-sm border flex justify-between items-center">
                        <div>
                            <div class="font-medium text-gray-800 text-sm" x-text="item.name"></div>
                            <div class="text-xs text-gray-500" x-text="'₹' + item.price.toFixed(2) + ' each'"></div>
                        </div>
                        <div class="flex items-center gap-2">
                            <button @click="decreaseQty(index)" class="w-7 h-7 bg-gray-100 hover:bg-gray-200 rounded font-bold text-gray-700">-</button>
                            <span class="w-6 text-center font-bold text-sm" x-text="item.quantity"></span>
                            <button @click="increaseQty(index)" class="w-7 h-7 bg-gray-100 hover:bg-gray-200 rounded font-bold text-gray-700">+</button>
                            <span class="w-16 text-right font-bold text-gray-800 text-sm" x-text="'₹' + (item.price * item.quantity).toFixed(2)"></span>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Cart Footer (Checkout) -->
            <div class="p-4 bg-white border-t space-y-3 shadow-lg">
                <div class="flex justify-between text-sm text-gray-600">
                    <span>Subtotal:</span>
                    <span class="font-medium" x-text="'₹' + subtotal().toFixed(2)"></span>
                </div>
                <div class="flex justify-between items-center text-sm text-gray-600">
                    <span>Discount (₹):</span>
                    <input type="number" x-model.number="discount" min="0" class="w-20 border rounded px-2 py-1 text-right text-sm">
                </div>
                <div class="flex justify-between text-xl font-black text-gray-900 border-t pt-2">
                    <span>Total Amount:</span>
                    <span class="text-emerald-600" x-text="'₹' + total().toFixed(2)"></span>
                </div>
                <!-- Customer Phone (Optional) -->
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Customer Mobile (Optional)</label>
                    <input type="tel" x-model="customerPhone" placeholder="e.g. 9876543210" 
                        class="w-full border rounded-lg px-3 py-1.5 text-sm bg-gray-50 focus:bg-white focus:outline-none focus:ring-1 focus:ring-emerald-500">
                </div>

                <!-- Payment Methods -->
                <div class="grid grid-cols-3 gap-2 pt-2">
                    <button type="button" @click="paymentMethod = 'cash'" :class="paymentMethod === 'cash' ? 'border-emerald-600 bg-emerald-50 text-emerald-800 font-bold' : 'border-gray-200'" class="border-2 py-2 rounded-lg text-xs text-center">💵 Cash</button>
                    <button type="button" @click="paymentMethod = 'upi'" :class="paymentMethod === 'upi' ? 'border-emerald-600 bg-emerald-50 text-emerald-800 font-bold' : 'border-gray-200'" class="border-2 py-2 rounded-lg text-xs text-center">📱 UPI / QR</button>
                    <button type="button" @click="paymentMethod = 'card'" :class="paymentMethod === 'card' ? 'border-emerald-600 bg-emerald-50 text-emerald-800 font-bold' : 'border-gray-200'" class="border-2 py-2 rounded-lg text-xs text-center">💳 Card</button>
                </div>

                <!-- Submit Button -->
                <button 
                    @click="submitOrder()" 
                    :disabled="cart.length === 0 || isSubmitting"
                    class="w-full bg-emerald-600 hover:bg-emerald-700 disabled:opacity-50 text-white font-bold py-3.5 rounded-xl shadow-md transition text-base">
                    <span x-show="!isSubmitting">Print & Complete Bill (₹<span x-text="total().toFixed(2)"></span>)</span>
                    <span x-show="isSubmitting">Saving & Printing...</span>
                </button>
            </div>
        </div>
    </div>

    <script>
        function posApp() {
            return {
                activeCategory: 'all',
                cart: [],
                discount: 0,
                customerPhone: '',
                paymentMethod: 'cash',
                isSubmitting: false,
                showSuccessNotification: false,

                addToCart(productId, name, price) {
                    const existing = this.cart.find(item => item.product_id === productId);
                    if (existing) {
                        existing.quantity++;
                    } else {
                        this.cart.push({ product_id: productId, name: name, price: parseFloat(price), quantity: 1 });
                    }
                },
                increaseQty(index) {
                    this.cart[index].quantity++;
                },
                decreaseQty(index) {
                    if (this.cart[index].quantity > 1) {
                        this.cart[index].quantity--;
                    } else {
                        this.cart.splice(index, 1);
                    }
                },
                subtotal() {
                    return this.cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
                },
                total() {
                    return Math.max(0, this.subtotal() - (this.discount || 0));
                },
                 resetCart() {
                    this.cart = [];
                    this.discount = 0;
                    this.customerPhone = ''; // <--- reset
                    this.paymentMethod = 'cash';
        },
                async submitOrder() {
                    if (this.cart.length === 0) return;
                    this.isSubmitting = true;

                    try {
                        const response = await fetch("{{ route('pos.checkout') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                                "Accept": "application/json"
                            },
                            body: JSON.stringify({
                                items: this.cart,
                                payment_method: this.paymentMethod,
                                discount: this.discount,
                                 customer_phone: this.customerPhone
                            })
                        });

                        const data = await response.json();
                        if (data.success) {
                            // 1. Open receipt in print popup
                            window.open(data.redirect, '_blank', 'width=420,height=600');

                            // 2. Instantly reset the current bill for next customer
                            this.resetCart();

                            // 3. Show success toast notification
                            this.showSuccessNotification = true;
                            setTimeout(() => {
                                this.showSuccessNotification = false;
                            }, 3500);
                        } else {
                            const errorMsg = data.message || (data.errors ? Object.values(data.errors).flat().join("\n") : "Order failed. Please try again.");
                            alert(errorMsg);
                        }
                    } catch (e) {
                        alert("Network error: " + e.message);
                    } finally {
                        this.isSubmitting = false;
                    }
                }
            }
        }
    </script>
</body>
</html>