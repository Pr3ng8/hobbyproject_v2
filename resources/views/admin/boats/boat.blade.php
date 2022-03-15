@extends('layouts.main')

@section('content')
<style>

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
</style>

<div class="container p-3 shadow-sm mb-5 rounded" style="background-color: #FFFFFF;">

    @if(empty($boat) || is_null($boat) || !is_object($boat))

    <div class="d-flex justify-content-center">
        We couldn't find the boat,sorry!
    </div>

    @else

    <!-- Row thats contains the name of the boat -->
    <div class="row justify-content-center mx-1">

        <!-- Name of the boat col -->
        <div class="col-lg-11 col-md-10 col-sm-10 left-to-top pl-3 mx-1">
            <h1 class="mb-0 display-4">{{ $boat->name }}</h1>
        </div>
        <!-- -->

    </div>
    <!-- Boat row name end -->

    <!-- Row thats contains the main data of the boat -->
    <div class="row my-2 mx-1 justify-content-center">

        <div class="col-lg-5 my-2 col-md-10 col-sm-10 col-xs-12 col-content p-2 align-self-center">
            <figure class="figure mx-auto d-block">
                <img src="{{ isset($boat->photos['file']) ? asset($boat->photos['file']) : 'https://via.placeholder.com/350x150' }}" class="img-figure img-fluid rounded mx-auto d-block" alt="A picture of the boat.">
            </figure>
        </div>

        <!-- Col thats conatains the data of the boat -->
        <div class="col-lg-5 my-2 col-md-10 col-sm-10 col-xs-12 col-content p-2 offset-lg-1 offset-md-0 offset-sm-0 offset-xs-0">

            <!-- The title of the content col -->
            <div class="row">
                <div class="col-12">
                    <p class="lead">Boat's Data</p>
                </div>
            </div>
            <!-- -->

            <!-- The Capacity of the boat -->
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <p class="font-weight-bold">Capacity</p>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <p>{{ $boat->limit }}</p>
                </div>
            </div>
            <!-- -->

            <!-- The status of the boat -->
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <p class="font-weight-bold">Status of the Boat</p>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <p>{{ $boat->trashed() ? "Deleted" : "Available" }}</p>
                </div>
            </div>
            <!-- -->

            <!-- The creation time of the boat -->
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <p class="font-weight-bold">Created at</p>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <p>{{ $boat->created_at }}</p>
                </div>
            </div>
            <!-- -->

            <!-- The update time of the boat -->
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <p class="font-weight-bold">Updated</p>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <p>{{ is_null($boat->updated_at) ? "There was no updated!" : $boat->updated_at }}</p>
                </div>
            </div>
            <!-- -->

            <!-- Row thats contains the edit form for the boat -->
            <div class="row">
                <div class="col-12 align-self-end">
                    <form action="{{ action([App\Http\Controllers\AdminBoatsController::class,'edit'], ['id' => $boat->id] ) }}" method="POST">
                        @csrf
                        @method('GET')
                        <button type="submit" class="btnSubmit m-2 float-right">
                            <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                                <path fill="#FCBC80" d="M2,11L4.05,11.1C4.3,8.83 5.5,6.85 7.25,5.56L6.13,3.84C5.86,3.36 6,2.75 6.5,2.47C7,2.2 7.59,2.36 7.87,2.84L8.8,4.66C9.78,4.24 10.86,4 12,4C13.14,4 14.22,4.24 15.2,4.66L16.13,2.84C16.41,2.36 17,2.2 17.5,2.47C18,2.75 18.14,3.36 17.87,3.84L16.75,5.56C18.5,6.85 19.7,8.83 19.95,11.1L22,11A1,1 0 0,1 23,12A1,1 0 0,1 22,13L19.95,12.9C19.7,15.17 18.5,17.15 16.75,18.44L17.87,20.16C18.14,20.64 18,21.25 17.5,21.53C17,21.8 16.41,21.64 16.13,21.16L15.2,19.34C14.22,19.76 13.14,20 12,20C10.86,20 9.78,19.76 8.8,19.34L7.87,21.16C7.59,21.64 7,21.8 6.5,21.53C6,21.25 5.86,20.64 6.13,20.16L7.25,18.44C5.5,17.15 4.3,15.17 4.05,12.9L2,13A1,1 0 0,1 1,12A1,1 0 0,1 2,11M9.07,11.35C9.2,10.74 9.53,10.2 10,9.79L8.34,7.25C7.11,8.19 6.27,9.6 6.05,11.2L9.07,11.35M12,9C12.32,9 12.62,9.05 12.9,9.14L14.28,6.45C13.58,6.16 12.81,6 12,6C11.19,6 10.42,6.16 9.72,6.45L11.1,9.14C11.38,9.05 11.68,9 12,9M14.93,11.35L17.95,11.2C17.73,9.6 16.89,8.19 15.66,7.25L14,9.79C14.47,10.2 14.8,10.74 14.93,11.35M14.93,12.65C14.8,13.26 14.47,13.8 14,14.21L15.66,16.75C16.89,15.81 17.73,14.4 17.95,12.8L14.93,12.65M12,15C11.68,15 11.38,14.95 11.09,14.86L9.72,17.55C10.42,17.84 11.19,18 12,18C12.81,18 13.58,17.84 14.28,17.55L12.91,14.86C12.62,14.95 12.32,15 12,15M9.07,12.65L6.05,12.8C6.27,14.4 7.11,15.81 8.34,16.75L10,14.21C9.53,13.8 9.2,13.26 9.07,12.65Z" />
                            </svg>
                            Edit
                        </button>
                    </form>
                </div>
            </div>
            <!-- -->

        </div>
        <!-- -->

    </div>
    <!-- -->

    @endif

</div>

@endsection
