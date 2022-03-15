@extends('layouts.main')

@section('content')
<style>
.btnSubmit {
    width: 40%;
    border: 2px solid #0583F2;
    border-radius: 2rem;
    padding: 1.5%;
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
</style>

<div class="container p-3 shadow-sm p-3 mb-5 rounded" style="background-color: #FFFFFF;">

    <!-- Title of the page -->
    <h1 class="display-3 text-left mb-3">Search for User(s)</h1>
    <hr>
    <div class="container-fluid">
    <!-- Gird lyout tahts contains the input fields -->

        <!-- Search form we sumbitting for search -->
        <form action="{{ action([App\Http\Controllers\AdminUsersController::class, 'search']) }}" method="GET">

            @csrf
            @method('GET')


            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                      <label for="first_name">First Name:</label>
                      <input type="text" name="first_name" id="first_name" value="{{ Request::get('first_name') ?? '' }}" class="form-control" placeholder="John" aria-describedby="first_name_desc" />
                      <small id="first_name_desc" class="text-muted">The first name of the user.</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="last_name">Last Name:</label>
                        <input type="text" name="last_name" id="last_name" value="{{ Request::get('last_name') ?? '' }}" class="form-control" placeholder="Cash" aria-describedby="last_name_desc" />
                        <small id="last_name_desc" class="text-muted">The last name of the user.</small>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                      <label for="email">Email:</label>
                      <input type="email" name="email" id="email" value="{{ Request::get('email') ?? '' }}" class="form-control" placeholder="example@gmail.com" aria-describedby="email_desc">
                      <small id="email_desc" class="text-muted">The email address thats belongs to the user.</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="birthdate">BirthDate:</label>
                        <input type="text" name="birthdate" id="birthdate" value="{{ Request::get('birthdate') ?? '' }}" class="form-control" placeholder="2000-01-01" aria-describedby="birthdate_desc" pattern="^\d{4}\-(0[1-9]|1[012])\-(0[1-9]|[12][0-9]|3[01])$"/>
                        <small id="birthdate_desc" class="text-muted">The birthday of the user.</small>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="roles">User's Role:</label>
                        <select class="custom-select" name="roles" id="roles" aria-describedby="roles_desc">
                            <option value="" {{ Request::get('roles') ? 'selected' : '' }}>All</option>
                            @if(!empty($roles) || !is_null($roles) || count($roles) > 0 || !isset($roles))
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}" {{ Request::get('roles') ===  $role->name ? 'selected' : '' }}>{{ ucfirst($role->name) }}</option>
                                @endforeach
                            @endif
                        </select>
                        <small id="roles_desc" class="text-muted">The role the user has,basic is user.</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="status">User's Reservation status:</label>
                        <select class="custom-select" name="status" id="status" aria-describedby="status_desc">
                            <option value="" {{ empty(Request::get('status')) ? 'selected' : '' }}>All</option>
                            <option value="1" {{ Request::get('status') ===  "1" ? 'selected' : '' }}>Allowed</option>
                            <option value="0" {{ Request::get('status') ===  "0" ? 'selected' : '' }}>Disallowed</option>
                        </select>
                        <small id="status_desc" class="text-muted">The user's reservation status, that determinate if the user can make a reservation.</small>
                    </div>
                </div>
            </div>

            <div class="row justify-content-end">
                <div class="col-md-6">
                    <label for="userstatus">User Status:</label>
                    <select name="userstatus" class="form-control" id="userstatus" aria-describedby="userstatus_desc">
                        <option value="all" {{ empty(Request::get('userstatus')) ? 'selected' : ''}}>All</option>
                        <option value="active" {{ Request::get('userstatus') === 'active' ? 'selected' : '' }}>Active Users</option>
                        <option value="trashed" {{ Request::get('userstatus') === 'trashed' ? 'selected' : '' }}>Deleted Users</option>
                    </select>
                    <small id="userstatus_desc" class="text-muted">Determinate if the user is banned or active.</small>
                </div>


                <div class="col-md-6 align-self-end">
                    <button type="submit" class="btnSubmit m-2 float-right">
                        <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                            <path fill="#0583F2" id="searchicon" d="M9.5,3A6.5,6.5 0 0,1 16,9.5C16,11.11 15.41,12.59 14.44,13.73L14.71,14H15.5L20.5,19L19,20.5L14,15.5V14.71L13.73,14.44C12.59,15.41 11.11,16 9.5,16A6.5,6.5 0 0,1 3,9.5A6.5,6.5 0 0,1 9.5,3M9.5,5C7,5 5,7 5,9.5C5,12 7,14 9.5,14C12,14 14,12 14,9.5C14,7 12,5 9.5,5Z" />
                        </svg>
                        Search
                    </button>
                </div>
            </div>

        </form>
        <!-- -->

    </div>
    <hr>

    <!-- Result of the search -->
    <div class="container-fluid">

        @if(empty($users) || is_null($users) || !is_iterable($users) || sizeof($users) === 0)

        <div class="row justify-content-md-center">
            <div class="col-md-auto">
                <p class="display-4">No User(s)</p>
            </div>
        </div>

        @else

            @include('includes.alert')

            <table class="table table-hover table-responsive-md table-sm">
                <caption>List of users</caption>
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Full Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">User Role</th>
                        <th scope="col">Permission</th>
                        <th scope="col">Check</th>
                        <th scope="col">Delete\Restore</th>
                    </tr>
                </thead>
            <tbody>

            @foreach($users as $user)
            <tr>
                <th scope="row">{{ $user->id }}</th>
                    <td><a href="{{ route('admin.user.show', ['id' => $user->id] )}}">{{ $user->getFullName() }}</a></td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->getRole() }}</td>
                    <td>{{ $user->hasPermission() ? "Allowed" : "Disallowed" }}</td>
                    <td>
                        <form action="{{ action([App\Http\Controllers\AdminUsersController::class,'show'], ['id' => $user->id])}}" method="POST">
                            @csrf
                            @method('GET')
                            <button type="submit" class="btn btn-info" alt="Check user profile">
                            <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                                <path fill="#000000" d="M2,3H22C23.05,3 24,3.95 24,5V19C24,20.05 23.05,21 22,21H2C0.95,21 0,20.05 0,19V5C0,3.95 0.95,3 2,3M14,6V7H22V6H14M14,8V9H21.5L22,9V8H14M14,10V11H21V10H14M8,13.91C6,13.91 2,15 2,17V18H14V17C14,15 10,13.91 8,13.91M8,6A3,3 0 0,0 5,9A3,3 0 0,0 8,12A3,3 0 0,0 11,9A3,3 0 0,0 8,6Z" />
                            </svg>
                            </button>
                        </form>
                    </td>
                    <td>
                    @if( $user->trashed() )

                    <form method="POST" action="{{ action([App\Http\Controllers\AdminUsersController::class,'restore'], ['id' => $user->id]) }}">
                        @csrf
                        @method('POST')
                        <button type="submit" class="btn btn-success" alt="restore">
                        <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                            <path fill="#000000" d="M14,14H16L12,10L8,14H10V18H14V14M6,7H18V19C18,19.5 17.8,20 17.39,20.39C17,20.8 16.5,21 16,21H8C7.5,21 7,20.8 6.61,20.39C6.2,20 6,19.5 6,19V7M19,4V6H5V4H8.5L9.5,3H14.5L15.5,4H19Z" />
                        </svg>
                        </button>
                    </form>

                    @else

                    <form method="POST" action="{{ action([App\Http\Controllers\AdminUsersController::class,'destroy'], ['id' => $user->id]) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" alt="Delete">
                        <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                            <path fill="#000000" d="M20.37,8.91L19.37,10.64L7.24,3.64L8.24,1.91L11.28,3.66L12.64,3.29L16.97,5.79L17.34,7.16L20.37,8.91M6,19V7H11.07L18,11V19A2,2 0 0,1 16,21H8A2,2 0 0,1 6,19Z" />
                        </svg>
                        </button>
                    </form>

                    @endif
                </td>
            </tr>
            @endforeach

            </tbody>
            </table>

            <!-- Pagination  -->
            <div class="container d-flex mx-auto">
            <div class="d-flex mx-auto">{{ $users->appends([
            'first_name' => Request::get('first_name') ?? '',
            'last_name' => Request::get('last_name') ?? '',
            'email' => Request::get('email') ?? '',
            'birthdate' => Request::get('birthdate') ?? '',
            'roles' => Request::get('roles') ?? '',
            'status' => Request::get('status') ?? '',
            'userstatus' => Request::get('userstatus') ?? '',
            ])->links() }}</div>
            </div>
            <!--  -->

        @endif

</div>

@endsection
