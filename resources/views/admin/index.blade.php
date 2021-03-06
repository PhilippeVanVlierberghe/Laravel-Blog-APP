@extends('layouts.master')

@section('content')
    @if(Session::has('info'))
        <div class="rowExtra">
            <div class="col-md-12">
                <p class="alert alert-info">{{ Session::get('info') }}</p>
            </div>
        </div>
    @endif

    <div class="rowExtra">
        <div class="col-md-12">
            <a href="{{ route('admin.create') }}" class="btn btn-success">New Post</a>
        </div>
    </div>
    <hr>
    @foreach ($posts as $post)
        @if (Auth::user()->id == $post->user_id)
        <div class="rowExtra">
            <div class="col-md-12">
                <p><strong>{{$post->title}}</strong> 
                    <a href="{{ route('admin.edit', ['id' => $post->id]) }}">Edit</a>
                    <a href="{{ route('admin.delete', ['id' => $post->id]) }}">Delete</a>
                </p>
            </div>
        </div>
        @endif
    @endforeach
@endsection
