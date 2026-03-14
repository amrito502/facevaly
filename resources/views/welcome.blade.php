 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 h-screen flex items-center justify-center">

    <div class="text-center bg-white p-10 rounded-xl shadow-lg">
        <h1 class="text-4xl font-bold text-gray-800 mb-6">
            Welcome to My Website
        </h1>

        <p class="text-gray-500 mb-8">
            Please login or register to continue
        </p>

        <div class="flex justify-center gap-4">
            <a href="{{ route('login') }}" 
               class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
               Login
            </a>

            <a href="{{ route('register') }}" 
               class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
               Register
            </a>
        </div>
    </div>

</body>
</html>
