@extends('_layouts.master')

@section('content')
<article class="markdown">
    <h1>{{ $page->title }}</h1>
    <p class="border-grey-light border-b pb-0 mb-8">{{ date('F j, Y', $page->date) }}</p>
    @yield('post')

    <footer class="border-grey border-t-2 pt-4 pb-8 text-center">
        @if ($page->getNext())
            <a href="{{ $page->getNext()->getPath() }}" class="text-blue-dark hover:text-blue-darker no-underline">&lt;&lt; {{  $page->getNext()->title }}</a>
        @endif

        @if ($page->getPrevious())
            <a href="{{ $page->getPrevious()->getPath() }}" class="text-blue-dark hover:text-blue-darker no-underline">{{  $page->getPrevious()->title }} >></a>
        @endif
    </footer>
</article>
@endsection