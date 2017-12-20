@extends('layouts.master')
@section('content')
    {{--/*Control Structures*/--}}
    {{--<div class="row">--}}
    {{--<h1>Control Structures</h1>--}}
    {{--</div>--}}
    {{--/*if else in Laravel.*/--}}
    {{--@if(false)--}}
    {{--<p>This only displays if it is True.</p>--}}
    {{--@else--}}
    {{--<p>This only displays if it is False.</p>--}}
    {{--@endif--}}
    {{--/* For Loop in Laravel*/--}}
    {{--@for($i = 0; $i<=5; $i++)--}}
    {{--<p>{{ $i+1 }} . Iteration</p>--}}
    {{--@endfor--}}

    {{--<hr>--}}
    {{--<h1>XSS</h1>--}}
    {{--{!! "<script> alert('Hello World'); </script>" !!}--}}
    {{--{{ "<script> alert('Hello World'); </script>" }}--}}

    <div class="row">
        <div class="col-md-12">
            <p class="quote">The beautiful Laravel</p>
        </div>
    </div>
    @foreach($posts as $post)
        <div class="row">
            <div class="col-md-12 text-center">
                {{--<h1 class="post-title">{{ $post['title'] }}</h1>--}}
                <h1 class="post-title">{{ $post->title }}</h1>
                <p style="font-weight: bold;">
                    @foreach($post->tags as $tag)
                        -{{ $tag->name }}-
                    @endforeach
                </p>
                {{--<p>{{ $post['content'] }}</p>--}}
                <p>{{ $post->content }}</p>
                <p><a href="{{ route('blog.post', ['id' => $post->id]) }}">Read more...</a></p>
            </div>
        </div>
        <hr>
    @endforeach
    <div class="row">
        <div class="col-md-12 text-center">
            {{ $posts->links() }}
        </div>
    </div>
@endsection