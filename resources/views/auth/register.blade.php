<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Admin - Fresh Cafe</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-md border border-gray-100">
        
        <div class="text-center mb-6">
            <span class="text-4xl">👤</span>
            <h1 class="text-2xl font-bold text-gray-800 mt-2">Create Admin Account</h1>
            <p class="text-sm text-gray-500">Register new manager credentials</p>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg text-sm mb-4">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Full Name</label>
                <input type="text" name="name" value="{{ old('name') }}" required 
                       class="w-full border rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" required 
                       class="w-full border rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Password (min 6 chars)</label>
                <input type="password" name="password" required 
                       class="w-full border rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Confirm Password</label>
                <input type="password" name="password_confirmation" required 
                       class="w-full border rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>

            <button type="submit" 
                    class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 rounded-lg shadow-md transition">
                Register Admin
            </button>
        </form>

        <div class="mt-6 text-center text-sm text-gray-500">
            Already have an account? 
            <a href="{{ route('login') }}" class="text-emerald-600 hover:underline font-semibold">Sign In</a>
        </div>

    </div>

</body>
</html>