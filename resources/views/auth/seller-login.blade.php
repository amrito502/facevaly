<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <!-- Viewport for all devices -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Seller Login</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="w-full max-w-md bg-white shadow-lg rounded-2xl p-8">

        <h2 class="text-2xl font-bold text-center mb-6 text-gray-800">
            Seller Login
        </h2>

        <form method="POST" action="{{ route('seller.login.submit') }}" class="space-y-4">
            @csrf

            <!-- Email -->
            <div>
                <input
                    type="email"
                    name="email"
                    placeholder="Seller Email"
                    value="{{ old('email') }}"
                    class="w-full px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500"
                >
            </div>

            <!-- Password -->
            <div>
                <input
                    type="password"
                    name="password"
                    placeholder="Password"
                    class="w-full px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500"
                >
            </div>

            <!-- Error Message -->
            @error('email')
                <p class="text-red-500 text-sm">{{ $message }}</p>
            @enderror

            <!-- Button -->
            <button
                type="submit"
                class="w-full bg-green-600 text-white py-3 rounded-xl hover:bg-green-700 transition duration-200"
            >
                Login
            </button>
        </form>

    </div>

</body>
</html>
