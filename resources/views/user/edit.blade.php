@extends('layouts.main')

@section('content')

<style>
img {
    height: 350px;
    width: 350px;
}

.img-overlay {
  position: absolute;
  top: 0;
  bottom: 0;
  left: 0;
  right: 0;
  text-align: center;
}

.img-overlay:before {
  content: ' ';
  display: block;
  height: 50%;
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
    border: 2px solid #4F8C6C;
    border-radius: 2rem;
    padding: 1.5%;
    cursor: pointer;
    color: #4F8C6C;
}

.btnSubmit:hover,
.btnSubmit:focus {
    color: #fff;
    text-decoration:none;
    background-color: #4F8C6C
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
    p {
        font-size: 12px;
    }

    .display-4 {
        font-size: 32px;
    }
}

</style>

<div class="container rounded p-3 shadow-sm mb-5" style="background-color: #FFFFFF;">
    <!-- The user\s profile picture -->
    <div class="row mb-4 justify-content-center position-relative">

        <!-- USer's profile picture -->
        <img src="{{ isset($user->photos['file']) ? asset($user->photos['file']) : 'https://via.placeholder.com/350x350' }}" class="mx-auto img-circle " id="userProfilePicture" alt="User's profile picture." />
        <!-- -->

        <!-- Overlay for the image button -->
        <div class="img-overlay">
            <!-- Button for showing upload model -->
            <div class="btn" data-toggle="modal" id="uploadbtn" data-target=".bd-example-modal-sm">
                <svg style="width:32px;height:32px" viewBox="0 0 32 32">
                    <path fill="#BFBEBD" d="M9,16V10H5L12,3L19,10H15V16H9M5,20V18H19V20H5Z" />
                </svg>
            </div>
            <!-- -->
        </div>
        <!-- -->

    </div>
    <!-- -->


    <!-- User FUll Name on the top -->
    <div class="row justify-content-center">
        <div class="col-lg-11 col-md-10 col-sm-10 left-to-top pl-3">
            <h1 class="mb-0 display-4">{{ $user->getFullName() }}</h1>
        </div>
    </div>
    <!-- -->

    <!-- Personal data form -->
    <form action="{{ action([App\Http\Controllers\UserController::class,'update'], [ 'id' => $user->id ]) }}" method="POST" enctype="multipart/form-data" files="true">
        <div class="row my-2 justify-content-center">

            <!-- User's personal Data -->
            <div class="col-lg-5 my-2 col-md-10 col-sm-10 col-xs-12 col-content justify-items-center p-2">

                <!-- The title of the col -->
                <div class="row">
                    <div class="col-12">
                        <p class="lead">Personal Data</p>
                    </div>
                </div>
                <!-- -->

                <!-- The First name of the user -->
                <div class="row mb-2">
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <p class="font-weight-bold mb-0">First Name</p>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6">
                    <input type="text" value="{{ $user->first_name }}" class="form-control" name="first_name" id="first_name" placeholder="Johnny" required>
                    </div>
                </div>
                <!-- -->

                <!-- Last Name of the user -->
                <div class="row mb-2">
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <p class="font-weight-bold mb-0">Last Name</p>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <input type="text" value="{{ $user->last_name }}" class="form-control" name="last_name" id="last_name" placeholder="Cash" required>
                    </div>
                </div>
                <!-- -->

                <!-- The user's email address -->
                <div class="row mb-2">
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <p class="font-weight-bold mb-0">Email</p>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <input type="email" value="{{ $user->email }}" class="form-control" name="email" id="email" placeholder="example@gmail.com" required>
                    </div>
                </div>
                <!-- -->

                <!-- The birthdate of the user -->
                <div class="row mb-2">
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <p class="font-weight-bold mb-0">BirthDate</p>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <input type="text" value="{{ $user->birthdate }}" class="form-control" name="birthdate" id="birthdate" placeholder="2000-01-01" pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}" required>
                    </div>
                </div>
                <!-- -->

                <!-- Submit Button place for the form-->
                <div class="row">
                    <div class="col-12 align-self-end">
                        @csrf
                        @method('PUT')
                        <!-- Save Button -->
                        <button type="submit" class="btnSubmit m-2 float-right">
                            <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                                <path fill="#4F8C6C" d="M15,9H5V5H15M12,19A3,3 0 0,1 9,16A3,3 0 0,1 12,13A3,3 0 0,1 15,16A3,3 0 0,1 12,19M17,3H5C3.89,3 3,3.9 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V7L17,3Z" />
                            </svg>
                            Save
                        </button>
                        <!-- -->
                    </div>
                </div>
                <!-- -->

                <!-- Hidden filed for the user's profile picture update/upload -->
                <input type="file" class="form-control" name="file" id="file" accept=".png, .jpg, .jpeg, .bmp" hidden>
                <!-- -->

                <!-- Includeing errors -->
                @include('includes.errors')
                <!-- -->
                <!-- Includeing alert -->
                @include('includes.alert')
                <!-- -->
            </div>
        </form>
        <!-- Presonal data row end -->


        <!-- Some extra information about the user -->
        <div class="col-lg-5 my-2 col-md-10 col-sm-10 col-xs-12 col-content offset-lg-1 offset-md-0 offset-xs-0 offset-sm-0 p-2">
            <div class="row">
                <div class="col-12">
                    <p class="lead">Statistics</p>
                </div>
            </div>
                <!-- Number of reservation that the user made -->
                <div class="row">
                    <div class="col-6">
                        <p class="font-weight-bold">Number of Reservation made</p>
                    </div>
                    <div class="col-4">
                        <p>{{ $user->reservations->count() == 0 ? "No Reservation Made..." : $user->reservations->count() }}</p>
                    </div>
                </div>
                <!-- -->

                <!-- Number of the comments that the user made-->
                <div class="row">
                    <div class="col-6">
                        <p class="font-weight-bold">Number of Comments</p>
                    </div>
                    <div class="col-4">
                        <p>{{ $user->comments->count() == 0 ? "No Thoughts Shared..." : $user->comments->count() }}</p>
                    </div>
                </div>
                <!-- -->
                @can('post.create')
                <!-- If the user is author we show how much post the user made -->
                <div class="row">
                    <div class="col-6">
                        <p class="font-weight-bold">Posts Created</p>
                    </div>
                    <div class="col-4">
                        <p>{{ $user->posts->count() == 0 ? "No Post Created..." : $user->posts->count() }}</p>
                    </div>
                </div>
                <!-- -->
                @endcan
                <!-- The role what the user has -->
                <div class="row">
                    <div class="col-6">
                        <p class="font-weight-bold">Role</p>
                    </div>
                    <div class="col-4">
                        <p>{{ empty($user->getRole()) ?  "No Role" : $user->getRole()  }}</p>
                    </div>
                </div>
                <!-- -->

        </div>
        <!-- -->

    </div>
<script>

$(function () {

    function readURL(input) {

        if (input.files && input.files[0]) {

            if ( /\.(jpe?g|png|gif|bmp)$/i.test(input.files[0].name) ) {

                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#userProfilePicture').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
    }

    $("#uploadbtn").click(function(e){
       e.preventDefault();

       $("#file").trigger('click');
    });

    $("#file").change(function() {
        readURL(this);
    });

});

</script>

</div>

@endsection
