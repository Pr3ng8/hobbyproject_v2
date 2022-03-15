@extends('layouts.main')

@section('js')
<!-- Scripts for Dropzone -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.js" integrity="sha256-cs4thShDfjkqFGk5s2Lxj35sgSRr4MRcyccmi0WKqCM=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone-amd-module.min.js" integrity="sha256-resMHNTFeF3aRsPgzKyH1bIwSmB4dUlHGTE5nfldZLI=" crossorigin="anonymous"></script>
<!-- -->
@endsection

@section('css')
<!-- Css for Dropzone -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.css" integrity="sha256-e47xOkXs1JXFbjjpoRr1/LhVcqSzRmGmPqsrUQeVs+g=" crossorigin="anonymous" />
<!-- -->
@endsection

@section('content')


<div class="container p-3 rounded" style="background-color: #FFFFFF;">
<h1 class="display-4 text-left mb-3">Edit News</h1>
    @include('includes.errors')
    @include('includes.alert')
    <form action="{{ action([App\Http\Controllers\AuthorPostsController::class, 'update'], ['id' => $post->id]) }}" method="POST" enctype="multipart/form-data" file="true">

    @csrf
    @method('PUT')

        <div class="form-group">
            <label for="title">Title of the Post:</label>
            <input type="text" name="title" value="{{ $post->title }}" class="form-control" aria-describedby="titleofthepost"  placeholder="Breaking News">
            @if ( $errors->has('title') )
                @foreach( $errors->get('title') as $title_error )
                <div class="alert alert-danger" role="alert">
                    {{ $title_error }}
                </div>
                @endforeach
            @endif
        </div>

        <div class="form-group">
            <label for="body">Content of the Post:</label>
            <textarea class="form-control" name="body" id="body" rows="4"  placeholder="So, In this article....">{{ $post->body }}</textarea>
            @if ( $errors->has('body') )
                @foreach( $errors->get('body') as $body_error )
                <div class="alert alert-danger" role="alert">
                    {{ $body_error }}
                </div>
                @endforeach
            @endif
        </div>

        <div class="form-group">
            <label for="file">Photo for the post:</label>
            <input type="file" name="file" class="form-control-file" id="exampleFormControlFile1">
            @if ( $errors->has('file') )
                @foreach( $errors->get('file') as $file_error )
                <div class="alert alert-danger" role="alert">
                    {{ $file_error }}
                </div>
                @endforeach
            @endif
        </div>

        <div class="d-flex flex-row-reverse">
            <button type="submit" class="btn btn-success px-4 float-right">Save</button>
        </div>
    </form>

</div>


@endsection
