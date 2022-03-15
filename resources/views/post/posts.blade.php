@extends('layouts.main')

@section('content')
<style>
img {
    height: 250px;
    width: 100%;
    object-fit: cover;
    background-size:contain
    -webkit-background-size: cover;
    -moz-background-size: cover;
    -o-background-size: cover;
    background-size: cover;
}

.btnSubmit {
    width: 30%;
    border: 2px solid #f77062;
    border-radius: 2rem;
    padding: 1%;
    cursor: pointer;
    color: #f77062;
}

.btnSubmit:hover,
.btnSubmit:focus {
    color: #fff;
    text-decoration:none;
    background-color: #f77062
}

.btnSubmit{
    font-weight: 600;
    background-color: transparent;
}

.btnSubmit:hover path,
.btnSubmit:focus  path {
    fill: #fff;
}
@media screen and (max-width: 768px) {
    img {
        height: 150px;
    }

    .btnSubmit{
        font-size: 12px;
        width: 50%;
        font-weight: 600;
    }
}
</style>
<div class="container p-3 rounded">
<!-- Title of the page -->
<h1 class="display-3 text-left text-white mb-3">News</h1>
<hr>
@include('includes.errors')
@include('includes.alert')
<div class="row justify-content-center">
@foreach($posts as $post)


    <div class="col-11 p-0 shadow-sm mb-3" style="background-color: #FFFFFF;">
        <div class="col-12 w-100 p-0 m-0">
            <img class="" src="{{ isset($post->photos['file']) ?  $post->photos['file'] : 'https://via.placeholder.com/1151x250' }}" alt="Image for the post.">
        </div>

        <div class="row px-3 mt-1">

            <!-- The title of the post -->
            <div class="col-12">
                <h5 class="card-title">{{ $post->title }}</h5>
            </div>
            <!--  -->

            <!-- The body of the post goes here -->
            <div class="col-12">
                <p class="card-text">{{ substr( $post->body, 0, 70) }}{{ strlen($post->body) >= 70 ? "..." : "" }}</p>
            </div>
            <!--  -->

            <!-- The creation date of the post -->
            <div class="col-12">
                <blockquote class="blockquote m-1">
                    <footer class="blockquote-footer">Posted By {{isset($post->user->first_name )? $post->user->first_name : "Unknown"}}</footer>
                </blockquote>
            </div>
            <!-- -->

            <!-- The time of the post when it was created -->
            <div class="col-6">
                <p class="card-text"><small class="text-muted">{{ $post->created_at->format('M D o h:m:s') }}</small></p>
            </div>
             <!-- -->

            <!-- Link to the full post -->
            <div class="col-6 align-self-end mb-2">
                <a href="{{ route('post', ['id' => $post->id] ) }}" class="btnSubmit float-right text-center">Read</a>
            </div>
            <!--  -->

        </div>

    </div>


@endforeach

        <div class="col-lg-12 col-md-10 col-sm-8 col-xs-6">
            <!-- Pagination  -->
            <div class="container d-flex mx-auto">
                <div class="d-flex mx-auto">{{ $posts->links() }}</div>
            </div>
            <!--  -->
        </div>

    </div>

</div>
@endsection
