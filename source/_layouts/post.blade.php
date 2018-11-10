@extends('_layouts.master')

@section('meta')
    <meta name="twitter:card" content="summary">
    <meta name="twitter:site" content="@horuskol">
    <meta name="twitter:creator" content="@horuskol">

    <meta property="og:url" content="{{ $page->getPath() }}">
    <meta property="og:title" content="{{ $page->title }}">

    @if ($page->description)
        <meta property="og:description" content="{{ $page->description }}">
    @endif

    @if ($page->image)
        <meta property="og:image" content="{{ $page->image }}" />
    @endif
@endsection

@section('content')
<article class="markdown">
    <h1>{{ $page->title }}</h1>
    <p class="border-grey-light border-b pb-0 mb-8">{{ date('F j, Y', $page->date) }}</p>
    @yield('post')

    <footer class="border-grey border-t-2 pt-4 pb-8 text-center">
        @if ($page->getNext())
            <a href="{{ $page->getNext()->getPath() }}" class="text-blue-dark hover:text-blue-darker no-underline">&lt;&lt; {{  $page->getNext()->title }}</a>
        @endif

        @if ($page->getNext() && $page->getPrevious())
            <span class="ml-8 mr-8"></span>
        @endif

        @if ($page->getPrevious())
            <a href="{{ $page->getPrevious()->getPath() }}" class="text-blue-dark hover:text-blue-darker no-underline">{{  $page->getPrevious()->title }} >></a>
        @endif
    </footer>
</article>
@endsection