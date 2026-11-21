<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Anang Ship - Jual Beli Tiket Kapal</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300..800&display=swap" rel="stylesheet">

    @vite('resources/css/app.css')
    @vite('resources/js/app.js')

    <style>
        body { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
    <script>
        (function() {
            const theme = localStorage.getItem('theme') || 'system';
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (theme === 'dark' || (theme === 'system' && prefersDark)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>
</head>

<body class="flex min-h-screen flex-col bg-[#f4f4f5] text-slate-800 antialiased dark:bg-slate-900 dark:text-slate-100">

    <header class="border-b border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900">
        <div class="mx-auto flex h-[88px] max-w-[1312px] items-center justify-between px-4 sm:px-6 lg:px-0">
            <a href="/" class="flex items-center gap-4">
                <img src="/img/logo3.png" alt="Anang Ship" class="h-12 w-12 object-contain">
                <span class="text-xl font-bold uppercase tracking-tight text-[#c8175d]">Anang Ship</span>
            </a>
            <div class="flex items-center gap-3">
                @include('partials.theme-switcher')
                <a href="/" class="inline-flex items-center gap-2 text-sm font-semibold text-[#c8175d] hover:underline">
                    <i class="bi bi-arrow-left"></i> Kembali ke beranda
                </a>
            </div>
        </div>
    </header>

    <main class="flex flex-1 items-center justify-center px-4 py-10 sm:px-6">
        @yield('container')
    </main>

    <footer class="mx-auto flex w-full max-w-[1312px] flex-col gap-3 px-4 py-8 text-xs text-slate-500 sm:px-6 md:flex-row md:items-center md:justify-between lg:px-0">
        <p>&copy; {{ date('Y') }} ANANG SHIP. Teman perjalanan laut Anda.</p>
        <div class="flex gap-6">
            <a href="#" class="hover:text-[#c8175d]">Syarat &amp; Ketentuan</a>
            <a href="#" class="hover:text-[#c8175d]">Kebijakan Privasi</a>
            <a href="/informasi" class="font-medium text-[#c8175d]">Bantuan</a>
        </div>
    </footer>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
</body>

</html>