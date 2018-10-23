<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <link rel="stylesheet" href="{{ mix('css/main.css') }}">

        <title>HorusKol: The Blog</title>
    </head>

    <body class="max-w-lg border-t-6 ml-auto mr-auto border-indigo bg-grey-lightest text-black">
        <header class="pb-4">
            <h1 class="mt-4 ml-10"><a href="/" class="no-underline text-black hover:bg-grey">HorusKol: The Blog</a></h1>
            <h2 class="mt-4 ml-10 mr-25pc">Adventures and musings in the world of web development</h2>
        </header>

        <div style="border-radius: 2rem 25% 0% 0% / 100% 100% 0% 0%;" class="h-8 min-h-0 ml-3 mr-3 pt-2 pb-2 bg-indigo-lighter">
            {{--<nav>--}}
                {{--<ul>--}}
                    {{--<li class="inline-block"><a href="/" class="text-grey-darkest">Home</a></li>--}}
                {{--</ul>--}}
            {{--</nav>--}}
        </div>

        <div class="ml-3 mr-3 pl-10 pr-10 min-h-screen-half bg-grey-lighter">
            @yield('content')
        </div>

        <div style="border-radius: 0% 0% 2rem 25% / 0% 0% 100% 100%;" class="h-8 ml-3 mr-3 bg-indigo-lighter"></div>

        <footer class="m-4 mr-8 mb-8 text-right">
            <ul>
                <li class="inline-block mr-4">
                    <a href="https://twitter.com/horus_kol">twitter</a>
                </li>
                <li class="inline-block mr-4">
                    <a href="https://www.facebook.com/horuskol">facebook</a>
                </li>
                <li class="inline-block mr-4">
                    &copy; Stuart Jones
                </li>
        </footer>
    </body>
</html>
