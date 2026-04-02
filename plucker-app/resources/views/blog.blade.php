<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Fonts -->
    <meta name="author" content="Abir Joshi">

    <!-- Social media share -->
    <meta charset="UTF-8">
    <title>{{$blog->title}} - tAM</title>
    <meta name="description" content="{{$blog->sub_heading}}">
    <meta name="keywords" content="{{$blog->title}}">
    <meta name="author" content="The API Middleware Team">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta property="og:title" content="{{$blog->title}}">
    <meta property="og:description" content="{{$blog->sub_heading}}">
    <meta property="og:url" content="https://theapimidfleware.com/blog/{{$blog->slug}}">
    <meta property="og:type" content="website">
    <meta property="og:image" content="{{$blog->img}}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{$blog->title}}">
    <meta name="twitter:description" content="{{$blog->sub_heading}}">
    <meta name="twitter:image" content="{{$blog->img}}">


    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png">
    <link rel="icon" type="image/x-icon" href="/favicon/favicon.ico">
    <link rel="manifest" href="/favicon/site.webmanifest">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
    <link href="https://fonts.bunny.net/css?family=inter:200,400,500,600,700,800&display=swap" rel="stylesheet" />
    <style>
        /* Hide scrollbar for Chrome, Safari and Opera */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        /* Hide scrollbar for IE, Edge and Firefox */
        .no-scrollbar {
            -ms-overflow-style: none;
            /* IE and Edge */
            scrollbar-width: none;
            /* Firefox */
        }
    </style>
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
</head>
@vite(['resources/css/app.css', 'resources/js/app.js'])
<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>

<body class="font-[inter] antialiased">

    <nav class='py-6 px-8'>
        <div class="flex w-full items-center">
            <div class='grid grid-cols-12 w-full'>
                <div class="col-span-6 md:col-span-3 flex items-center md:justify-center">
                    <svg width="70" height="23" viewBox="0 0 70 23" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M6.31173 23V19.9877H3.15586V10.8798H0V6.06009H3.15586V0H7.71035V6.06009H17.2497V10.8798H7.71035V18.1803H15.851V15.1325H20.4414V19.9877H17.2497V23H6.31173Z" fill="#404040" />
                        <path d="M24.8303 23V6.06009H27.9862V3.04776H31.142V0H35.7324V3.04776H38.8882V6.06009H42.08V23H37.4896V16.9399H29.3848V23H24.8303ZM29.3848 12.0847H37.4896V7.86749H34.3337V4.85516H32.5406V7.86749H29.3848V12.0847Z" fill="#404040" />
                        <path d="M46.4386 23V3.04776H49.5945V0H54.149V3.04776H57.3407V9.10786H59.0979V3.04776H62.2896V0H66.88V3.04776H70V23H65.4814V4.85516H63.6883V10.8798H60.4965V19.9877H55.9421V10.8798H52.7503V4.85516H50.9931V23H46.4386Z" fill="#404040" />
                    </svg>
                </div>
                <div class='hidden md:col-span-6 md:flex items-center justify-center'>
                    <div class="flex gap-10">
                        <a href="/#Howitworks" class='font-semibold text-sm'>How it works</a>
                        <a href="/#Features" class='font-semibold text-sm'>Features</a>
                        <a href="/#Pricing" class='font-semibold text-sm'>Pricing</a>
                        <a href="/#FAQ" class='font-semibold text-sm'>FAQ</a>
                        <a href="/blog" class='font-semibold text-sm'>Blogs</a>
                    </div>
                </div>
                <div class='col-span-6 md:col-span-3 flex items-center justify-center'>
                    <a href="/login" class='font-semibold text-sm text-blue-600'>Login</a>
                    <a href="/register" class='font-semibold text-sm py-2 px-4 border-2 border-blue-600 text-blue-600 rounded-lg ml-5'>Sign up</a>
                </div>

            </div>

        </div>
    </nav>
    @if($slug !='home')
    <div class='md:flex justify-center w-full'>
        <div class='p-8 w-full md:p-4 md:w-6xl md:flex'>
            <div class='w-full md:mt-18'>
                <div>
                    <div>
                        <h1 class='capitalize font-medium text-5xl'>{{$blog->title}}</h1>
                    </div>
                    <div>
                        <h3 class='capitalize text-lg font-semibold text-gray-500 mt-2 mb-1'>By {{$blog->author}}</h3>
                        <h3 class='capitalize text-xs text-gray-500 mb-2'>{{$blog->created_at}}</h3>
                        <div>
                            <p class='text-lg pt-4 pb-4'>{{$blog->sub_heading}}</p>
                        </div>
                    </div>
                </div>
                <div>
                    <img class='w-full rounded-lg mt-4 mb-4' src="{{$blog->img}}" alt="">
                </div>
                <div class='mt-2 mb-2'>
                    {!!$blog->body!!}
                </div>
                <div class='mt-2 mb-2'>
                
                </div>

            </div>
            <div class='md:mt-42 w-xs pl-8'>

                <div class='md:mt-21'>

                    <h2 class='text-xl font-semibold text-gray-900'>Other Blogs</h2>

                    @foreach($links as $link)
                    <div class='mt-2'>
                        <a href="/blog/{{$link->slug}}" class='text-sm text-gray-600'>{{$link->title}}</a>
                    </div>

                    @endforeach

                </div>
            </div>
        </div>
    </div>
    <div class='md:p-0 w-full md:mt-8 md:flex md:justify-center items-center mb-16'>
        <div class='w-full md:w-6xl mt-16 border border-r-0 border-l-0 border-gray-300'>
            <div class='md:flex p-4 items-center'>
                <div>
                    <h2 class='text-2xl md:text-6xl text-gray-800 max-w-lg font-semibold mt-4'>Build. Manage. Log. Secure.</h2>
                    <p class='text-gray-400 mt-4 text-lg'>It is that simple.</p>
                </div>
                <div class='mt-4 mb-4 md:mt-0 md:mb-0 md:ml-auto'>
                    <a href="https://calendly.com/joshi-theapimiddleware/30min" class='bg-blue-600 hover:bg-blue-700 hiver:shadow-lg text-white font-semibold text-sm py-3 px-4 rounded-lg'>Get a Demo</a>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class='md:flex justify-center w-full'>
        <div class='p-8 w-full md:p-4 md:w-6xl md:flex'>
            <div class='md:mt-32'>
                <h2 class='text-3xl'>Blogs</h2>
                <div class='mt-16 mb-2'>
                    @foreach($links as $l)

                    <div class='md:flex mb-4'>
                        <img src="{{$l->img}}" class='h-50 rounded-xl'alt="">
                        <div class='mb-2 pl-8 mt-4'>
                            <a href="/blog/{{$l->slug}}" class='text-xl'>{{$l->title}}</a>
                            <p class='text'>{{$l->sub_heading}}</p>
                            <p class='text-xs text-gray-400'>{{$l->created_at}}</p>
                        </div>
                    </div>

                    @endforeach

                    @if(!count($links) >0)
                    <p class='text-sm text-gray-600'>There are no blogs yet</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
    <div class='md:h-32' id='spacer'>

    </div>
    <div class='md:p-0 w-full md:mt-8 md:flex md:justify-center items-center mb-16'>
        <div class='mt-4 w-full md:w-6xl'>
            <div class='p-2'>
                <svg width="70" height="23" viewBox="0 0 70 23" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6.31173 23V19.9877H3.15586V10.8798H0V6.06009H3.15586V0H7.71035V6.06009H17.2497V10.8798H7.71035V18.1803H15.851V15.1325H20.4414V19.9877H17.2497V23H6.31173Z" fill="#404040" />
                    <path d="M24.8303 23V6.06009H27.9862V3.04776H31.142V0H35.7324V3.04776H38.8882V6.06009H42.08V23H37.4896V16.9399H29.3848V23H24.8303ZM29.3848 12.0847H37.4896V7.86749H34.3337V4.85516H32.5406V7.86749H29.3848V12.0847Z" fill="#404040" />
                    <path d="M46.4386 23V3.04776H49.5945V0H54.149V3.04776H57.3407V9.10786H59.0979V3.04776H62.2896V0H66.88V3.04776H70V23H65.4814V4.85516H63.6883V10.8798H60.4965V19.9877H55.9421V10.8798H52.7503V4.85516H50.9931V23H46.4386Z" fill="#404040" />
                </svg>
            </div>
            <div class='p-2'>
                <p class='text-sm text-gray-400 w-xs'>
                    the API Middleware to Log, Secure, Monitor your APIs to prevent Data-leaks
                </p>
            </div>
            <div class='flex gap-4 p-2 text-sm'>
                <a href="/privacy-policy">Privacy Policy</a>
                <a href="/terms-of-service">Terms of service</a>
            </div>
            <div class='p-2 text-sm'>
                <p>© 2025 Plucker Securities Limited. All rights reserved.</p>
            </div>
        </div>
    </div>
    <div class='md:h-32' id='spacer'>

    </div>

</body>

<script src="https://unpkg.com/flowbite@1.4.1/dist/flowbite.js"></script>
<!-- Google Tag Manager (noscript) -->
@livewire('page-logger',['email'=>'Guest','url'=>'/test'])
@livewireScripts
<!-- End Google Tag Manager (noscript) -->
</body>

</html>