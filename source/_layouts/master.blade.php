<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="theme-color" content="#bcdefa">
        <link rel="stylesheet" href="{{ mix('css/main.css') }}">

        @if ($page->title)
            <title>HorusKol - {{ $page->title }}</title>
        @else
            <title>HorusKol</title>
        @endif
    </head>

    <body class=" border-blue-light border-t-8 bg-grey-lightest text-black">
        <div class="max-w-lg ml-auto mr-auto">
            <header class="pb-4">
                <h1 class="mt-4 ml-5 md:ml-10"><a href="/" class="no-underline text-black hover:bg-grey">HorusKol</a></h1>
                <h2 class="mt-4 ml-5 md:ml-10 mr-25pc">Adventures and musings in the world of web development</h2>
            </header>

            <div style="border-radius: 2rem 100% 0% 0% / 100% 100% 0% 0%;" class="h-8 min-h-0 ml-3 mr-3 pt-2 pb-2 bg-blue-lighter">
                <nav>
                    <ul class="ml-5 sm:ml-10 p-0">
                        <li class="inline-block mr-4"><a href="/" class="text-blue-dark hover:text-blue-darker no-underline">Blog</a></li>
                        <li class="inline-block"><a href="/about" class="text-blue-dark hover:text-blue-darker no-underline">About</a></li>
                    </ul>
                </nav>
            </div>

            <div class="ml-3 mr-3 pl-2 pr-2 md:pl-10 md:pr-10 min-h-screen-half bg-grey-lighter">
                @yield('content')
            </div>

            <footer style="border-radius: 0% 0% 4rem 100% / 0% 0% 100% 100%;" class="ml-3 mr-3 mb-8 pt-2 pb-2 bg-blue-lighter text-right">
                <ul>
                    <li class="inline-block mr-4">
                        <a href="https://twitter.com/horus_kol" class="text-blue-dark hover:text-blue-darker no-underline social-icon twitter">twitter</a>
                    </li>
                    <li class="inline-block mr-4">
                        <a href="https://www.facebook.com/horuskol" class="text-blue-dark hover:text-blue-darker no-underline social-icon facebook">facebook</a>
                    </li>
                    <li class="inline-block mr-4">
                        <a href="https://www.linkedin.com/in/horuskol/" class="text-blue-dark hover:text-blue-darker no-underline social-icon linkedin">linkedin</a>
                    </li>
                </ul>

                <p class="pt-4 mr-8">
                    &copy; Stuart Jones
                </p>
            </footer>
        </div>
    </body>
</html>
