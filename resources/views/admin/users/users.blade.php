@extends('layouts.main')

@section('content')
<style>
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
<h1 class="display-3 text-left mb-3">Handel Users</h1>
<hr>
<!-- Search form for users -->
<form class="mb-4" method="GET" action="{{ action([App\Http\Controllers\AdminUsersController::class,'index']) }}">

    <div class="row">
    <!-- Role filter select -->
    <div class="col-lg-2 col-md-3 col-sm-4">
        <label for="usersrolelistmode">User Role</label>
        <select name="roles" class="form-control" id="usersrolelistmode">
            <option value="" {{ Request::get('roles') !== null ? '' : 'selected'}}>All</option>
            @if(!isset($roles) || !is_null($roles) || !empty($roles) || count($roles) > 0)
                @foreach ($roles as $role)
                    <option value="{{ $role->name }}" {{ strcmp( $role->name, Request::get('roles') ) === 0 ? 'selected' : ''}}>{{ ucfirst($role->name) }}</option>
                @endforeach
            @endif

        </select>
    </div>
    <!-- -->

    <!-- Status filter select -->
    <div class="col-lg-2 col-md-3 col-sm-4">
        <label for="status">Reservation Status</label>
        <select name="status" class="form-control" id="status">
            <option value="" {{ empty(Request::get('status'))  ? 'selected' : '' }}>All</option>
            <option value="1" {{ Request::get('status') === '1' ? 'selected' : '' }}>Allowed</option>
            <option value="0" {{ Request::get('status') === '0' ? 'selected' : '' }}>Disallowed</option>
        </select>
    </div>
    <!-- -->

    <!-- User's status filter select -->
    <div class="col-lg-2 col-md-3 col-sm-4">
        <label for="userstatuslistmode">User Status</label>
        <select name="userstatus" class="form-control" id="userstatus">
            <option value="all" {{ empty(Request::get('userstatus')) ? 'selected' : ''}}>All</option>
            <option value="active" {{ Request::get('userstatus') === 'active' ? 'selected' : '' }}>Active Users</option>
            <option value="trashed" {{ Request::get('userstatus') === 'trashed' ? 'selected' : '' }}>Deleted Users</option>
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
  @include('includes.errors')
</form>
<!-- End of search form -->
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
    'name' => Request::get('name') ?? '',
    'status' => Request::get('status') ?? '',
    'userstatus' => Request::get('userstatus') ?? 'all'
    ])->links() }}</div>
</div>
<!--  -->

@endif

</div>

@endsection
