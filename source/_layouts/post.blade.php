@extends('_layouts.master')

@section('meta')
    <meta name="twitter:card" content="summary">
    <meta name="twitter:site" content="@horuskol">
    <meta name="twitter:creator" content="@horuskol">
    <meta property="twitter:url" content="{{ $page->getPath() }}">
    <meta property="twitter:title" content="{{ $page->title }}">
    @if ($page->description)
        <meta name="description" content="{{ $page->description }}">
        <meta property="twitter:description" content="{{ $page->description }}">
    @endif
    @if ($page->image)
        <meta property="twitter:image" content="{{ $page->image }}" />
    @endif
    @if ($page->imageDescription)
        <meta property="twitter:image:alt" content="{{ $page->imageDescription }}">
    @endif
@endsection

@section('content')
<article>
    <header class="border-grey-light border-b pb-0 mb-8">
        <h1 class="font-bold text-2xl pt-8 pb-4">{{ $page->title }}</h1>
        <p class="">{{ date('F j, Y', $page->date) }}</p>
    </header>

    <main class="markdown">
        @yield('post')
    </main>

    <footer class="border-grey border-t-2 pt-4 pb-8">
        <ul class="list-reset pb-8">
            @foreach($page->tags as $tag)
                <li class="inline-block mr-4">
                    <a href="/blog/tags/{{ $tag }}" class="font-medium text-blue-700 visited:text-purple-700 hover:text-indigo-500 underline">{{ $tag }}</a>
                </li>
            @endforeach
        </ul>

        <ul class="list-reset flex flex-wrap justify-between">
            @if ($page->getNext())
                <li class="pr-2 pt-4 flex-grow whitespace-no-wrap">
                    <a href="{{ $page->getNext()->getPath() }}" class="font-medium text-blue-700 visited:text-purple-700 hover:text-indigo-500 underline">&lt;&lt; {{  $page->getNext()->title }}</a>
                </li>
            @endif

            @if ($page->getPrevious())
                <li class="pl-2 pt-4 flex-grow whitespace-no-wrap text-right">
                    <a href="{{ $page->getPrevious()->getPath() }}" class="font-medium text-blue-700 visited:text-purple-700 hover:text-indigo-500 underline">{{  $page->getPrevious()->title }} >></a>
                </li>
            @endif
        </ul>
    </footer>
</article>
@endsection