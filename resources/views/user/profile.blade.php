@extends('layouts.main')

@section('content')

<style>
img {
    height: 350px;
    width: 350px;
}

.img-circle {
    border-radius: 50%;
}

.left-to-top {
    border-top: 3px solid transparent;
    border-left: 5px solid transparent;
    border-image: linear-gradient(to bottom, #f77062 0%, #fe5196 100%);
    border-image-slice: 1;
}

.col-content {
    border-left: 5px solid transparent;
    border-image: linear-gradient(to top, #f77062 0%, #fe5196 100%);
    border-image-slice: 1;
    box-shadow:0 15px 25px rgba(0,0,0,.2);
}

.btnSubmit {
    width: 30%;
    border: 2px solid #FCBC80;
    border-radius: 2rem;
    padding: 1.5%;
    cursor: pointer;
    color: #FCBC80;
}

.btnSubmit:hover,
.btnSubmit:focus {
    color: #fff;
    text-decoration:none;
    background-color: #FCBC80
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
        height: 250px;
        width: 250px;
    }
    .display-4 {
        font-size: 32px;
    }
}
</style>

<div class="container rounded p-3 shadow-sm mb-5" style="background-color: #FFFFFF;">
    <!-- The user\s profile picture -->
    <div class="row mb-4 justify-content-center">
        <img src="{{ isset($user->photos['file']) ? asset($user->photos['file']) : 'https://via.placeholder.com/350x350' :  }}" class="mx-auto img-circle" alt="User's profile picture. ">
    </div>
    <!-- -->

    <!-- Full Name of the user -->
    <div class="row justify-content-center mx-1">
        <div class="col-lg-11 col-md-10 col-sm-10 left-to-top pl-3">
            <h1 class="mb-0 display-4">{{ $user->getFullName() }}</h1>
        </div>
    </div>
    <!-- -->

    <!-- Include alerts for session messages -->
    @include('includes.alert')
    <!-- -->

    <div class="row my-2 mx-1 justify-content-center">
        <!-- User's personal Data -->
        <div class="col-lg-5 my-2 col-md-10 col-sm-10 col-xs-12 col-content p-2">

            <!-- The title of the col -->
            <div class="row">
                <div class="col-12">
                    <p class="lead">Personal Data</p>
                </div>
            </div>
            <!-- -->

            <!-- The First name of the user -->
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <p class="font-weight-bold">First Name</p>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <p>{{ $user->first_name }}</p>
                </div>
            </div>
            <!-- -->

            <!-- Last Name of the user -->
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <p class="font-weight-bold">Last Name</p>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <p>{{ $user->last_name }}</p>
                </div>
            </div>
            <!-- -->

            <!-- The user's email address -->
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <p class="font-weight-bold">Email</p>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <p>{{ $user->email }}</p>
                </div>
            </div>
            <!-- -->

            <!-- The birthdate of the user -->
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <p class="font-weight-bold">BirthDate</p>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-4">
                    <p>{{ $user->birthdate }}</p>
                </div>
            </div>
            <!-- -->

            <div class="row">
                <div class="col-12 align-self-end">
                    <form action="{{ action(App\Http\Controllers\UserController::class,'edit', ['id' => $user->id] ) }}" method="POST">
                        @csrf
                        @method('GET')
                        <button type="submit" class="btnSubmit m-2 float-right">
                            <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                                <path fill="#FCBC80" d="M21.7,13.35L20.7,14.35L18.65,12.3L19.65,11.3C19.86,11.09 20.21,11.09 20.42,11.3L21.7,12.58C21.91,12.79 21.91,13.14 21.7,13.35M12,18.94L18.06,12.88L20.11,14.93L14.06,21H12V18.94M12,14C7.58,14 4,15.79 4,18V20H10V18.11L14,14.11C13.34,14.03 12.67,14 12,14M12,4A4,4 0 0,0 8,8A4,4 0 0,0 12,12A4,4 0 0,0 16,8A4,4 0 0,0 12,4Z" />
                            </svg>
                            Edit
                        </button>
                    </form>
                </div>
            </div>

        </div>
        <!-- -->

        <!-- Some extra information about the user -->
        <div class="col-lg-5 my-2 col-md-10 col-sm-10 col-xs-12 col-content offset-lg-1 offset-md-0 offset-xs-0 offset-sm-0 p-2">
            <div class="row">
                <div class="col-12">
                    <p class="lead">Statistics</p>
                </div>
            </div>
                <!-- Number of reservation that the user made -->
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <p class="font-weight-bold">Number of Reservation made</p>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <p>{{ $user->reservations->count() == 0 ? "Zero..." : $user->reservations->count() }}</p>
                    </div>
                </div>
                <!-- -->

                <!-- Number of the comments that the user made-->
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-3">
                        <p class="font-weight-bold">Number of Comments</p>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-3">
                        <p>{{ $user->comments->count() == 0 ? "No Thoughts Shared..." : $user->comments->count() }}</p>
                    </div>
                </div>
                <!-- -->
                @can('post.create')
                <!-- If the user is author we show how much post the user made -->
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-3">
                        <p class="font-weight-bold">Posts Created</p>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-3">
                        <p>{{ $user->posts->count() == 0 ? "No Post Created..." : $user->posts->count() }}</p>
                    </div>
                </div>
                <!-- -->
                @endcan
                <!-- The role what the user has -->
                <div class="row">
                    <div class="ccol-lg-6 col-md-6 col-sm-3">
                        <p class="font-weight-bold">Role</p>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-3">
                        <p>{{ empty($user->getRole()) ?  "No Role" : $user->getRole()  }}</p>
                    </div>
                </div>
                <!-- -->

        </div>
        <!-- -->
    </div>


</div>
<!-- Container thats holds the comment hadeling for the user -->
<div class="container rounded p-3 shadow-sm mb-5" style="background-color: #FFFFFF;">

@if(empty($comments) || is_null($comments) || !isset($comments) || count($comments) <= 0)

    <!-- No comments title if there are no comments -->
    <div class="row justify-content-center">
        <div class="col-lg-11 col-md-10 col-sm-10 left-to-top pl-3">
            <h1 class="mb-0 display-4">No Comments</h1>
        </div>
    </div>
    <!-- -->

@else

    <!-- Title if there are comments avabile -->
    <div class="row justify-content-center mx-1 mb-2">
        <div class="col-lg-11 col-md-10 col-sm-10 left-to-top pl-3">
            <h1 class="mb-0 display-4">Comments</h1>
        </div>
    </div>
    <!-- -->

    <div class="row justify-content-center mx-1">
        <div class="col-lg-11 col-md-10 col-sm-10">
            <table class="table table-hover table-responsive-md table-sm">
                <caption>Your Comments</caption>
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Post Title</th>
                        <th scope="col">Comment</th>
                        <th scope="col">Created at</th>
                        <th scope="col">Check</th>
                        <th scope="col">Delete</th>
                    </tr>
                </thead>
                <tbody>

                @foreach($comments as $comment)
                    <tr>
                        <th scope="row">{{ $comment->id }}</th>
                        <td>{{ $comment->post->title }}</a></td>
                        <td>{{ strlen($comment->body) >= 15 ? substr($comment->body, 0, 15) . " ... "  : $comment->body}}</td>
                        <td>{{ $comment->created_at }}</td>
                        <td>
                            <a class="" href="{{ route('post', ['id' => $comment->post->id] ) }}" alt="Check user profile">
                                <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                                    <path fill="grey" d="M20,12V16C20,17.11 19.11,18 18,18H13.9L10.2,21.71C10,21.89 9.76,22 9.5,22H9A1,1 0 0,1 8,21V18H4A2,2 0 0,1 2,16V6C2,4.89 2.9,4 4,4H9.5C8.95,4.67 8.5,5.42 8.14,6.25L7.85,7L8.14,7.75C9.43,10.94 12.5,13 16,13C17.44,13 18.8,12.63 20,12M16,6C16.56,6 17,6.44 17,7C17,7.56 16.56,8 16,8C15.44,8 15,7.56 15,7C15,6.44 15.44,6 16,6M16,3C18.73,3 21.06,4.66 22,7C21.06,9.34 18.73,11 16,11C13.27,11 10.94,9.34 10,7C10.94,4.66 13.27,3 16,3M16,4.5A2.5,2.5 0 0,0 13.5,7A2.5,2.5 0 0,0 16,9.5A2.5,2.5 0 0,0 18.5,7A2.5,2.5 0 0,0 16,4.5" />
                                </svg>
                            </a>
                        </td>
                        <td>
                            <form method="POST" action="{{ action([App\Http\Controllers\CommentsController::class,'destroy'], ['id' => $comment->id]) }}">
                                @csrf
                                @method('DELETE')
                                <a type="submit" alt="Delete">
                                    <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                                        <path fill="grey" d="M20.37,8.91L19.37,10.64L7.24,3.64L8.24,1.91L11.28,3.66L12.64,3.29L16.97,5.79L17.34,7.16L20.37,8.91M6,19V7H11.07L18,11V19A2,2 0 0,1 16,21H8A2,2 0 0,1 6,19Z" />
                                    </svg>
                                </a>
                            </form>
                        </td>
                    </tr>
                @endforeach

                </tbody>
            </table>
        </div>
    </div>

@endif
</div>
<!-- End of comments container -->
<script>

</script>
@endsection
