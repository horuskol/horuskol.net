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

    @if ($page->presentation)
        <title>HorusKol - {{ $page->presentation }}</title>
    @else
        <title>HorusKol</title>
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

<body class="bg-grey-lightest text-black">
    <nav class="bg-blue-lighter p-2">
        <ul class="mr-5 sm:mr-10 p-0">
            <li class="inline-block mr-4"><a href="/" class="text-blue-dark hover:text-blue-darker no-underline">HorusKol</a></li>
            <li class="inline-block mr-4"><a href="/presentations" class="text-blue-dark hover:text-blue-darker no-underline">Presentations</a></li>
        </ul>
    </nav>

    <header class="text-center">
        <h1 class="m-4 text-4xl">{{ $page->presentation }}</h1>
    </header>

    <div id="presentation-slide" class="presentation border-2">
        @yield('presentation')
    </div>

    <footer style="position: fixed; bottom: 0; left: calc(50% - 500px); width: 1000px;">
        <p class="text-right m-4">{{ date('F j, Y', $page->date) }} &copy; Stuart Jones</p>
    </footer>
</body>

<script type="text/javascript">
    window.slideUrls = [];
    window.slideIndex = 0;
    <?php $current = $page; ?>
    @while ($current && $current->presentation === $page->presentation)
        window.slideUrls.push('{{ $current->getPath() }}');
        <?php $current = $current->getNext(); ?>
    @endwhile
</script>

<script type="text/javascript" src="/assets/js/presentation.js"></script>
</html>