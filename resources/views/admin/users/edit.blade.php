
    @extends('layouts.main')

@section('content')

<style>
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

</style>

<div class="container rounded p-3 shadow-sm mb-5" style="background-color: #FFFFFF;">
    <!-- The user\s profile picture -->
    <div class="row mb-4 justify-content-center">
        <img src="https://via.placeholder.com/350x350" class="mx-auto img-circle" alt='User\'s profile picture.' >
    </div>
    <!-- -->

    <div class="row justify-content-center">
        <div class="col-lg-11 col-md-10 col-sm-10 left-to-top pl-3">
        <h1 class="mb-0 display-4">{{ $user->getFullName() }}</h1>
        </div>
    </div>
    <!-- Include alerts for session messages -->
    @include('includes.alert')
    <!-- -->


    <div class="row my-2 justify-content-center">

        <!-- User's personal Data -->
        <div class="col-lg-5 my-2 col-md-10 col-sm-10 col-xs-12 col-content p-2">
            <div class="row">
                <div class="col-12">
                    <p class="lead">Personal Data</p>
                </div>
            </div>
            <!-- The First name of the user -->
            <div class="row">
                <div class="col-4">
                    <p class="font-weight-bold">First Name</p>
                </div>
                <div class="col-4">
                    <p>{{ $user->first_name }}</p>
                </div>
            </div>
            <!-- -->

            <!-- Last Name of the user -->
            <div class="row">
                <div class="col-4">
                    <p class="font-weight-bold">Last Name</p>
                </div>
                <div class="col-4">
                    <p>{{ $user->last_name }}</p>
                </div>
            </div>
            <!-- -->

            <!-- The user's email address -->
            <div class="row">
                <div class="col-4">
                    <p class="font-weight-bold">Email</p>
                </div>
                <div class="col-4">
                    <p>{{ $user->email }}</p>
                </div>
            </div>
            <!-- -->

            <!-- The birthdate of the user -->
            <div class="row">
                <div class="col-4">
                    <p class="font-weight-bold">BirthDate</p>
                </div>
                <div class="col-4">
                    <p>{{ $user->birthdate }}</p>
                </div>
            </div>
            <!-- -->
            <form action="{{ action([App\Http\Controllers\AdminUsersController::class,'update'], ['id' => $user->id]) }}" method="POST">
                <!-- Creating custom select for role -->
                @if(empty($roles) || is_null($roles) || !is_object($roles))
                <p class="font-weight-bold text-primary">Sorry, something went wrong!</p>
                @else
                <div class="form-group">
                    <label class="font-weight-bold" for="role_id">User Role</label>
                    <select name="role_id" class="form-control form-control-sm font-weight-bold text-primary">
                        @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ $user->getRole() === $role->name ? 'selected' : ''}} >{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                <!-- -->

                <!-- Custome select so admin can change the user reservation status -->
                <div class="form-group">
                    <label class="font-weight-bold" for="status">User Status</label>
                    <select name="status" class="form-control form-control-sm font-weight-bold text-primary">
                            @if( $user->hasPermission() === 1)
                                <option value="1" selected>Allowed</option>
                                <option value="0">Not Allowed</option>
                            @else
                                <option value="1">Allowed</option>
                                <option value="0" selected>Not Allowed</option>
                            @endif
                    </select>
                </div>
                <!-- -->

                <!-- Save Button -->
                @csrf
                @method('PUT')
                <button type="submit" class="btnSubmit m-2 float-right">
                    <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                        <path fill="#4F8C6C" d="M15,9H5V5H15M12,19A3,3 0 0,1 9,16A3,3 0 0,1 12,13A3,3 0 0,1 15,16A3,3 0 0,1 12,19M17,3H5C3.89,3 3,3.9 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V7L17,3Z" />
                    </svg>
                    Save
                </button>
                <!-- -->

            </form>
            <!-- -->
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

        </div>
        <!-- -->
    </div>


</div>

@endsection






