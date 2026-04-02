<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>The API Middleware</title>

    <!-- Fonts -->
    <meta name="author" content="Abir Joshi">

    <!-- Social media share -->
    <meta charset="UTF-8">
    <title>The API Middleware - An API Oversight Solution</title>
    <meta name="description" content="The API Middleware helps you log, secure, and classify your REST APIs to prevent data leaks. Follow our simple steps to enhance your API security.">
    <meta name="keywords" content="API Middleware, API Security, REST API, Data Leak Prevention, API Logging, API Classification">
    <meta name="author" content="The API Middleware Team">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta property="og:title" content="The API Middleware - Log, Secure, Classify REST APIs">
    <meta property="og:description" content="Enhance your API security by logging, securing, and classifying your REST APIs to prevent data leaks.">
    <meta property="og:url" content="https://theapimiddleware.com/">
    <meta property="og:type" content="website">
    <meta property="og:image" content="https://theapimiddleware.com/image_og_1.png">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="The API Middleware - Log, Secure, Classify REST APIs">
    <meta name="twitter:description" content="Prevent data leaks by logging, securing, and classifying your REST APIs with The API Middleware.">
    <meta name="twitter:image" content="https://theapimiddleware.com/image_og_1.png">


    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png">
    <link rel="icon" type="image/x-icon" href="/favicon/favicon.ico">
    <link rel="manifest" href="/favicon/site.webmanifest">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#ffffff">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link rel="stylesheet" href="https://flowbite.com/docs/main.css?v=3.1.1a">
    <link href="https://fonts.bunny.net/css?family=inter:200,400,500,600,700,800&display=swap" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@9.0.3"></script>
    <script src="https://flowbite.com/docs/flowbite.min.js"></script>
    <!-- Google Tag Manager -->
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-9L7XGXBBSF"></script>
    
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-9L7XGXBBSF');
    </script>
    <!-- End Google Tag Manager -->
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

<body class="font-sans antialiased">

    <!-- Scripts -->

    </head>

    <body>

        {{ $slot }}


        
        <!-- Google Tag Manager (noscript) -->
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TK86RXLK"
                height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
        <!-- End Google Tag Manager (noscript) -->
    </body>
    @livewire('page-logger',['email'=>'Guest','url'=>'/test'])
    @livewireScripts
</html>