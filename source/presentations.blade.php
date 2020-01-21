@extends('_layouts/master')

@section('content')
    @foreach ($presentations->filter(function($value, $key) { return $value->summary; }) as $presentation)
        <div class="border-grey{{ $presentation == $presentations->last() ? '' : ' border-b-2'}}">
            <h2 class="pt-8 pb-4"><a href="{{ $presentation->getPath() }}" class="no-underline text-blue-dark hover:text-blue-darker">{{ $presentation->presentation }}</a></h2>
            <p class="pb-4">{{ date("F j, Y", $presentation->date) }}</p>
            <p class="pb-2">{{ $presentation->summary }}</p>
            <p class="pb-4"><a href="{{ $presentation->getPath() }}" class="text-blue-dark hover:text-blue-darker no-underline font-bold">start presentation</a></p>
        </div>
    @endforeach
@endsection