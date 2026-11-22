<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Fresh Cafe</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-md border border-gray-100">
        
        <div class="text-center mb-6">
            <span class="text-4xl">🔐</span>
            <h1 class="text-2xl font-bold text-gray-800 mt-2">Admin Login</h1>
            <p class="text-sm text-gray-500">Sign in to access sales reports & settings</p>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg text-sm mb-4">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="space-y-4">
            @csrf

             <!-- PUT IT RIGHT HERE: -->
    @if(request('action'))
        <input type="hidden" name="action" value="{{ request('action') }}">
    @endif

            <div>
                <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                       class="w-full border rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Password</label>
                <input type="password" name="password" required 
                       class="w-full border rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>

            <div class="flex items-center justify-between text-sm">
                <label class="flex items-center gap-2 text-gray-600">
                    <input type="checkbox" name="remember" class="rounded text-emerald-600">
                    Remember me
                </label>
                <a href="{{ route('register') }}" class="text-emerald-600 hover:underline font-medium">Create Admin</a>
            </div>

            <button type="submit" 
                    class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 rounded-lg shadow-md transition">
                Sign In
            </button>
        </form>

        <div class="mt-6 text-center">
            <a href="{{ route('pos.index') }}" class="text-sm text-gray-400 hover:text-gray-600">
                ← Back to Billing Counter
            </a>
        </div>

    </div>

</body>
</html>