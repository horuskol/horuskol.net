@extends('_layouts.master', ['page' => (object) ['title' => 'Presentations']])

@section('content')
    @foreach ($presentations->filter(function($value, $key) { return $value->summary; }) as $presentation)
        <div class="border-gray-500 border-b-2 last:border-b-0">
            <h2 class="text-xl pt-8 pb-1"><a href="{{ $presentation->getPath() }}" class="text-blue-800 visited:text-purple-800 hover:text-blue-500 underline">{{ $presentation->presentation }}</a></h2>
            <p class="italic pb-4">{{ date("F j, Y", $presentation->date) }}</p>
            <p class="pb-8">{{ $presentation->summary }} <a href="{{ $presentation->getPath() }}" class="text-blue-800 visited:text-purple-800 hover:text-blue-500 underline" aria-hidden="true">start</a></p>
        </div>
    @endforeach
@endsection