---
pagination:
  collection: posts
  perPage: 10
---
@extends('_layouts.master', ['page' => (object) ['title' => 'Blog']])

@section('content')
    @foreach ($pagination->items as $post)
        <div class="border-gray-500 border-b-2 last:border-b-0">
            <h2 class="text-xl pt-8 pb-1"><a href="{{ $post->getPath() }}" class="text-blue-800 hover:text-blue-500 underline">{{ $post->title }}</a></h2>
            <p class="italic pb-4">{{ date("F j, Y", $post->date) }}</p>
            <p class="pb-8">{{ $post->description }} <a href="{{ $post->getPath() }}" class="text-blue-800 hover:text-blue-500 underline" aria-hidden="true">read</a></p>
        </div>
    @endforeach

    <footer class="flex justify-between border-grey border-t-2 pt-4 pb-8">
        @if ($previous = $pagination->previous)
            <a href="{{ $pagination->first }}" class="text-blue-800 hover:text-blue-500 underline">&lt;&lt;</a>
            <a href="{{ $previous }}" class="text-blue-800 hover:text-blue-500 underline">&lt;</a>
        @else
            <span>&lt;&lt;</span>
            <span>&lt;</span>
        @endif

        @foreach ($pagination->pages as $pageNumber => $path)
            <a href="{{ $path }}"
               class="{{ $pagination->currentPage == $pageNumber ? 'font-bold' : '' }} text-blue-800 hover:text-blue-500 underline">
                {{ $pageNumber }}
            </a>
        @endforeach

        @if ($next = $pagination->next)
            <a href="{{ $next }}" class="text-blue-800 hover:text-blue-500 underline">&gt;</a>
            <a href="{{ $pagination->last }}" class="text-blue-800 hover:text-blue-500 underline">&gt;&gt;</a>
        @else
            <span>&gt;</span>
            <span>&gt;&gt;</span>
        @endif
    </footer>
@endsection