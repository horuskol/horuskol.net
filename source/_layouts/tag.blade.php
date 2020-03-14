@extends('_layouts.master')

@section('content')
    @foreach ($page->getPostsByTag($posts) as $post)
        <div class="border-gray-500 border-b-2 last:border-b-0">
            <h2 class="text-xl pt-8 pb-1"><a href="{{ $post->getPath() }}" class="text-blue-800 visited:text-purple-800 hover:text-blue-500 underline">{{ $post->title }}</a></h2>
            <p class="italic pb-4">{{ date("F j, Y", $post->date) }}</p>
            <p class="pb-8">{{ $post->description }} <a href="{{ $post->getPath() }}" class="text-blue-800 visited:text-purple-800 hover:text-blue-500 underline" aria-hidden="true">read</a></p>
        </div>
    @endforeach
@endsection