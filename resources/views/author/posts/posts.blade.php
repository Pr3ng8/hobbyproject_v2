@extends('layouts.main')

@section('content')
<style>
img {
    height: 50px;
    width: 100px;
}

.btnSubmit {
    width: 40%;
    border: 2px solid #0583F2;
    border-radius: 2rem;
    padding: 1%;
    cursor: pointer;
    color: #0583F2;
}

.btnSubmit:hover,
.btnSubmit:focus {
    color: #fff;
    text-decoration:none;
    background-color: #0475D9
}

.btnSubmit{
    font-weight: 600;
    background-color: transparent;
}

.btnSubmit:hover #searchicon,
.btnSubmit:focus  #searchicon {
    fill: #fff;
}

@media screen and (max-width: 1024px) {

    .btnSubmit{
        width: 80%;
    }

}

@media screen and (max-width: 570px) {

    .btnSubmit{
        width: 40%;
    }

}
</style>

<div class="container-fluid shadow-sm p-3 mb-5 rounded" style="background-color: #FFFFFF;">

<!-- Title of the page -->
<h1 class="display-3 text-left mb-3">Handel Posts</h1>
<hr>

<!-- Form for the filtering -->
<form class="mb-4" method="GET" action="{{ action([App\Http\Controllers\AuthorPostsController::class,'index']) }}">

    @method('GET')
    @csrf

    <!-- Row thats contains the form inputs and button -->
    <div class="row">

        <!-- Select options for filtering deleted or active or all post -->
        <div class="col-lg-2 col-md-3 col-sm-4">
            <label for="postsstatus">Posts Status</label>
            <select name="postsstatus" class="form-control" id="postsstatus">
                <option value="all" {{Request::get('postsstatus') === "all" ? "selected" : ""}}>All</option>
                <option value="active" {{Request::get('postsstatus') === "active" ? "selected" : ""}}>Active Posts</option>
                <option value="trashed" {{Request::get('postsstatus') === "trashed" ? "selected" : ""}}>Deleted Posts</option>
            </select>
        </div>
        <!-- -->

        <!-- Submit button for the form -->
        <div class="col-lg-3 col-md-3 col-sm-4 align-self-end mt-2">
            <button type="submit" class="btnSubmit">
                <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                    <path fill="#0583F2" id="searchicon" d="M9.5,3A6.5,6.5 0 0,1 16,9.5C16,11.11 15.41,12.59 14.44,13.73L14.71,14H15.5L20.5,19L19,20.5L14,15.5V14.71L13.73,14.44C12.59,15.41 11.11,16 9.5,16A6.5,6.5 0 0,1 3,9.5A6.5,6.5 0 0,1 9.5,3M9.5,5C7,5 5,7 5,9.5C5,12 7,14 9.5,14C12,14 14,12 14,9.5C14,7 12,5 9.5,5Z" />
                </svg>
                Search
            </button>
        </div>
        <!-- -->

    </div>
    <!-- End row -->

</form>
<!-- End of FOrm filtering -->

@if(empty($posts) || is_null($posts) || !is_iterable($posts) || sizeof($posts) === 0)

<!-- Title if there are no post -->
<div class="row justify-content-md-center">
    <div class="col-md-auto">
        <p>No Post found!</p>
    </div>
</div>
<!-- -->

@else

<!-- Includeing the alert view -->
@include('includes.alert')
<!-- -->

<!-- Table container -->
<div class="table-responsive table-sm">

    <table class="table table-hover">

            <thead class="thead-light">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Picture</th>
                    <th scope="col">Title</th>
                    <th scope="col">Created At</th>
                    <th scope="col">Edit</th>
                    <th scope="col">Delete\Restore</th>
                </tr>
            </thead>

            <tbody>
            @foreach($posts as $post)

                <tr>
                    <!-- The id of the post -->
                    <th scope="row">{{ $post->id }}</th>
                    <!-- -->

                    <!-- The photo for the post -->
                    <td>
                        <img src="{{ isset($post->photos['file']) ? URL::asset($post->photos['file']) : 'https://via.placeholder.com/100x50' }}" alt="Card image cap">
                    </td>
                    <!-- -->

                    <!-- The title of the post -->
                    <td><a href="{{ route('post', ['id' => $post->id] ) }}">{{ $post->title }}</a></td>


                    <!-- The time of the post when it was created -->
                    <td>{{ $post->created_at->format('M D o h:m:s') }}</td>
                    <!-- -->

                    <!-- Edit the post button -->
                    <td>
                        <form method="GET" action="{{ action([App\Http\Controllers\AuthorPostsController::class,'edit'], ['id' => $post->id]) }}">
                            @csrf
                            @method('GET')
                            <button type="submit" class="btn btn-warning" alt="Edit">
                            <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                                <path fill="#000000" d="M2,6V8H14V6H2M2,10V12H14V10H2M20.04,10.13C19.9,10.13 19.76,10.19 19.65,10.3L18.65,11.3L20.7,13.35L21.7,12.35C21.92,12.14 21.92,11.79 21.7,11.58L20.42,10.3C20.31,10.19 20.18,10.13 20.04,10.13M18.07,11.88L12,17.94V20H14.06L20.12,13.93L18.07,11.88M2,14V16H10V14H2Z" />
                            </svg>
                            </button>
                        </form>
                    </td>
                    <!-- -->

                    <!-- Restore or delete button for the post -->
                    <td>
                    @if( $post->trashed() )

                    <!-- We can restore the deleted post by clicking this icon -->
                        <form method="POST" action="{{ action([App\Http\Controllers\AuthorPostsController::class,'restore'], ['id' => $post->id]) }}">
                            @csrf
                            @method('POST')
                            <button type="submit" class="btn btn-success" alt="Restore">
                            <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                                <path fill="#000000" d="M14,14H16L12,10L8,14H10V18H14V14M6,7H18V19C18,19.5 17.8,20 17.39,20.39C17,20.8 16.5,21 16,21H8C7.5,21 7,20.8 6.61,20.39C6.2,20 6,19.5 6,19V7M19,4V6H5V4H8.5L9.5,3H14.5L15.5,4H19Z" />
                            </svg>
                            </button>
                        </form>
                    <!-- -->

                    @else

                    <!-- We can delete the post by clicking this icon -->
                        <form method="POST" action="{{ action([App\Http\Controllers\AuthorPostsController::class,'destroy'], ['id' => $post->id]) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" alt="Delete">
                            <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                                <path fill="#000000" d="M20.37,8.91L19.37,10.64L7.24,3.64L8.24,1.91L11.28,3.66L12.64,3.29L16.97,5.79L17.34,7.16L20.37,8.91M6,19V7H11.07L18,11V19A2,2 0 0,1 16,21H8A2,2 0 0,1 6,19Z" />
                            </svg>
                            </button>
                        </form>
                    <!-- -->

                    @endif
                    </td>
                </tr>
                <!-- End of delete or restore button -->
            @endforeach
        </tbody>
    </table>

</div>
<!--Table container end -->

<!-- Pagination  -->
<div class="container d-flex mx-auto">
    <div class="d-flex mx-auto">{{ $posts->appends([
    'postsstatus' => Request::get('postsstatus') ?? 'all'
    ])->links() }}</div>
</div>
<!--  -->

@endif

</div>
@endsection
