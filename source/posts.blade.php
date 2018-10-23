@extends('_layouts/master')

@section('content')
    @foreach ($posts as $post)
        <div class="border-grey border-b-2">
            <h2 class="pt-8 pb-4"><a href="{{ $post->getPath() }}" class="no-underline text-black hover:bg-grey">{{ $post->title }}</a></h2>
            <p class="pb-4">{{ date("d-m-Y", $post->date) }}</p>
            <p class="pb-4">{{ $post->excerpt() }}</p>
            <p class="pb-4 text-right"><a href="{{ $post->getPath() }}">more...</a></p>
        </div>
    @endforeach
@endsection