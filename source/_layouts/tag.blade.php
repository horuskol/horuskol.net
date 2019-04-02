@extends('_layouts.master')

@section('content')
    @foreach ($page->getPostsByTag($posts) as $post)
        <div class="border-grey{{ $post == $page->getPostsByTag($posts)->last() ? '' : ' border-b-2'}}">
            <h2 class="pt-8 pb-4"><a href="{{ $post->getPath() }}" class="no-underline text-blue-dark hover:text-blue-darker">{{ $post->title }}</a></h2>
            <p class="pb-4">{{ date("F j, Y", $post->date) }}</p>
            <p class="pb-4">{{ $post->excerpt() }}... <a href="{{ $post->getPath() }}" class="text-blue-dark hover:text-blue-darker no-underline font-bold">continue reading</a></p>
        </div>
    @endforeach
@endsection