<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Profile Layout')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-900 min-h-screen flex flex-col">

    <header class="bg-white p-4 shadow">
        <h1 class="text-xl font-bold">Profile Layout</h1>
    </header>

    <!-- Page Content -->
    <main>
        {{ $slot }}
    </main>

    <footer class="bg-white border-t p-4 text-center text-sm">
        &copy; {{ now()->year }} My Laravel App
    </footer>

</body>
</html>
