@extends('_layouts.master')

@section('content')
    <div class="markdown">
        <h1>{{ $page->title }}</h1>
        @yield('page')
    </div>
@endsection