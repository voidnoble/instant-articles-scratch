@extends('index')

@section('content')
    <h1>Welcome Instant Articles</h1>
    <ul>
        @foreach($articles as $article)
        <li>{{ $article->title }}</li>
        @endforeach
    </ul>
@endsection