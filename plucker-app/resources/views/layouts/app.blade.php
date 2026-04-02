<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>The API Middleware</title>
    <meta name="description" content="The API Middleware helps you log, secure, and classify your REST APIs to prevent data leaks.">
    <meta name="author" content="The API Middleware Team">
    <meta property="og:title" content="The API Middleware - Log, Secure, Classify REST APIs">
    <meta property="og:description" content="Enhance your API security by logging, securing, and classifying your REST APIs to prevent data leaks.">
    <meta property="og:url" content="https://theapimiddleware.com/">
    <meta property="og:type" content="website">
    <meta property="og:image" content="https://theapimiddleware.com/image_og_1.png">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png">
    <link rel="icon" type="image/x-icon" href="/favicon/favicon.ico">
    <link rel="manifest" href="/favicon/site.webmanifest">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />

    <!-- External libs -->
    <link rel="stylesheet" href="https://flowbite.com/docs/main.css?v=3.1.1a">
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@9.0.3"></script>
    <script src="https://flowbite.com/docs/flowbite.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <!-- Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-9L7XGXBBSF"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag() { dataLayer.push(arguments); }
        gtag('js', new Date());
        gtag('config', 'G-9L7XGXBBSF');
    </script>
</head>

<body class="font-sans antialiased bg-zinc-50 text-zinc-900">

    <x-banner />

    <div class="flex min-h-screen">

        <!-- Sidebar -->
        <aside class="w-56 shrink-0">
            @livewire('main.navigation-menu', ['domain' => $domain])
        </aside>

        <!-- Main column -->
        <div class="flex-1 min-w-0">

            <!-- Top header bar -->
            <header class="fixed top-0 right-0 left-56 z-20 bg-white border-b border-zinc-200 h-14">
                @livewire('main.domains', ['domain' => $domain, 'start_time' => $start_time, 'end_time' => $end_time])
            </header>

            <!-- Page content -->
            <main class="mt-14 px-6 py-6">

                <!-- Page title + breadcrumb -->
                <div class="mb-6">
                    <h1 class="text-xl font-semibold text-zinc-900">{{ $page }}</h1>
                    <nav class="flex items-center gap-1.5 mt-1 text-xs text-zinc-400" aria-label="Breadcrumb">
                        <a href="#" class="hover:text-zinc-600 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z" />
                            </svg>
                            Home
                        </a>
                        {{ $crumb }}
                    </nav>
                </div>

                {{ $slot }}

            </main>
        </div>
    </div>

    @stack('modals')
    @livewire('page-logger', ['email' => 'Guest', 'url' => '/test'])
    @livewireScripts

    <noscript>
        <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TK86RXLK" height="0" width="0" style="display:none;visibility:hidden"></iframe>
    </noscript>

</body>

</html>
