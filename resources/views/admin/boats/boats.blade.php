@extends('layouts.main')

@section('content')
<div class="container shadow-sm p-3 mb-5 rounded" style="background-color: #FFFFFF;">
    <!-- Title of the page -->
    <h1 class="display-3 text-left mb-3">Handel Boats</h1>
    <hr>

    @if(empty($boats) || is_null($boats) || !is_object($boats))

    <div class="d-flex justify-content-center">We couldn't find the boats,sorry!</div>

    @else

    @include('includes.alert')
    <div class="table-responsive-md">
        <table style="border-top: 0px;" class="table table-hover table-sm ">
            <caption>List of Boats</caption>
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Boat Name</th>
                    <th scope="col">Boat Capacity</th>
                    <th scope="col">Check</th>
                    <th scope="col">Delete\Restore</th>
                </tr>
            </thead>
        <tbody>

        @foreach($boats as $boat)
            <tr>
                <th scope="row">{{ $boat->id }}</th>
                    <td><a href="{{ route('admin.boats.show', ['id' => $boat->id] )}}">{{ $boat->name }}</a></td>
                    <td>{{ $boat->limit }}</td>
                    <td>
                        <form action="{{ action([App\Http\Controllers\AdminBoatsController::class,'show'], ['id' => $boat->id])}}" method="POST">
                            @csrf
                            @method('GET')
                            <button type="submit" class="btn btn-info btn-sm" alt="Check">
                            <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                                <path fill="#000000" d="M11,9H13V7H11M12,20C7.59,20 4,16.41 4,12C4,7.59 7.59,4 12,4C16.41,4 20,7.59 20,12C20,16.41 16.41,20 12,20M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M11,17H13V11H11V17Z" />
                            </svg>
                            </button>
                        </form>
                    </td>
                    <td>

                    @if( $boat->trashed() )

                    <form method="POST" action="{{ action([App\Http\Controllers\AdminBoatsController::class,'restore'], ['id' => $boat->id]) }}">
                        @csrf
                        @method('POST')
                        <button type="submit" class="btn btn-success btn-sm" alt="Restore">
                        <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                            <path fill="#000000" d="M14,14H16L12,10L8,14H10V18H14V14M6,7H18V19C18,19.5 17.8,20 17.39,20.39C17,20.8 16.5,21 16,21H8C7.5,21 7,20.8 6.61,20.39C6.2,20 6,19.5 6,19V7M19,4V6H5V4H8.5L9.5,3H14.5L15.5,4H19Z" />
                        </svg>
                        </button>
                    </form>

                    @else

                    <form method="POST" action="{{ action([App\Http\Controllers\AdminBoatsController::class,'destroy'], ['id' => $boat->id]) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" alt="Delete">
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
    </div>

    <!-- Pagination  -->
    <div class="container d-flex mx-auto">
        <div class="d-flex mx-auto">{{ $boats->appends([
        'status' => Request::get('status') ?? 'all',
        ])->links() }}</div>
    </div>
    <!--  -->

    @endif
</div>

@endsection
