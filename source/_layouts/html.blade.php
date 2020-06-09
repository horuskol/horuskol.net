<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="theme-color" content="#bcdefa">

        <link rel="alternate" type="application/atom+xml" href="/rss.xml" title="HorusKol Blog Feed">

        <link rel="apple-touch-icon" sizes="57x57" href="/assets/images/favicon/apple-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="/assets/images/favicon/apple-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="/assets/images/favicon/apple-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="/assets/images/favicon/apple-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="/assets/images/favicon/apple-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="/assets/images/favicon/apple-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="/assets/images/favicon/apple-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="/assets/images/favicon/apple-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="/assets/images/favicon/apple-icon-180x180.png">
        <link rel="icon" type="image/png" sizes="192x192"  href="/assets/images/favicon/android-icon-192x192.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/assets/images/favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="96x96" href="/assets/images/favicon/favicon-96x96.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/assets/images/favicon/favicon-16x16.png">
        <link rel="manifest" href="/assets/images/favicon/manifest.json">
        <meta name="msapplication-TileColor" content="#bcdefa">
        <meta name="msapplication-TileImage" content="/assets/images/favicon/ms-icon-144x144.png">

        @yield('meta')

        <link rel="stylesheet" href="{{ mix('css/main.css') }}">

        @if ($page->title)
            <title>HorusKol - {{ $page->title }}</title>
        @else
            <title>HorusKol</title>
        @endif

        @if ($page->canonical_url)
            <link rel="canonical" href="{{ $page->canonical_url }}">
        @endif

        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-128485347-2"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', 'UA-128485347-2');
        </script>
    </head>

    <body class="border-blue-300 border-t-8 bg-gray-100 text-black">
        <div class="max-w-3xl ml-auto mr-auto">
            <header class="pb-4">
                <h1 class="text-3xl mt-4 ml-5 md:ml-10"><a href="/" class="text-blue-700 visited:text-purple-700 hover:text-indigo-500 underline">HorusKol</a></h1>
                <h2 class="text-2xl mt-4 ml-5 md:ml-10 mr-25pc">Adventures and musings in the world of web development</h2>
            </header>

            <div style="border-radius: 3rem 100% 0% 0% / 100% 100% 0% 0%;" class="text-xl ml-3 mr-3 pt-2 pb-2 bg-blue-200">
                <nav class="w-3/4">
                    <ul class="ml-5 sm:ml-10 p-0">
                        <li class="inline-block mr-4"><a href="/" class="text-blue-700 visited:text-purple-700 hover:text-indigo-500 underline">Blog</a></li>
                        <li class="inline-block mr-4"><a href="https://apps.horuskol.net" class="text-blue-700 visited:text-purple-700 hover:text-indigo-500 underline">Apps</a></li>
                        <li class="inline-block mr-4"><a href="/presentations" class="text-blue-700 visited:text-purple-700 hover:text-indigo-500 underline">Presentations</a></li>
                        <li class="inline-block mr-4"><a href="/about" class="text-blue-700 visited:text-purple-700 hover:text-indigo-500 underline">About</a></li>
                        <li class="inline-block mr-4"><a href="/listening" class="text-blue-700 visited:text-purple-700 hover:text-indigo-500 underline">Listening</a></li>
                    </ul>
                </nav>
            </div>

            <div class="ml-3 mr-3 pl-2 pr-2 md:pl-10 md:pr-10 min-h-screen-half bg-gray-200">
                @yield('content')
            </div>

            <footer style="border-radius: 0% 0% 4rem 100% / 0% 0% 100% 100%;" class="flex flex-wrap justify-end ml-3 mr-3 mb-8 pt-2 pb-2 bg-blue-200 text-right">
                <a href="https://dev.to/horus_kol" class="mr-4 text-blue-700 visited:text-purple-700 hover:text-indigo-500 underline social-icon dev-to">dev.to</a>
                <a href="https://twitter.com/horus_kol" class="mr-4 text-blue-700 visited:text-purple-700 hover:text-indigo-500 underline social-icon twitter">twitter</a>
                <a href="https://www.linkedin.com/in/horuskol/" class="mr-4 text-blue-700 visited:text-purple-700 hover:text-indigo-500 underline social-icon linkedin">linkedin</a>

                <p class="w-full pt-4 pr-8">
                    &copy; Stuart Jones
                </p>
            </footer>
        </div>
    </body>
</html>
