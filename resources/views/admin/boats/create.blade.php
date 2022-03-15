@extends('layouts.main')

@section('content')
<style>
.btnSubmit {
    width: 20%;
    border: 2px solid #4F8C6C;
    border-radius: 2rem;
    padding: 0.5%;
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

.btnSubmit:hover #savehicon,
.btnSubmit:focus  #savehicon {
    fill: #fff;
}

@media screen and (max-width: 570px) {

    .btnSubmit{
        width: 40%;
    }

}
</style>

<div class="container p-3 shadow-sm mb-5 rounded" style="background-color: #FFFFFF;">
    <!--Title of the page -->
    <h1 class="display-4 text-left mb-3">Create Boat</h1>
    <!-- -->

    <!-- Form for creating new boat -->
    <form action="{{ action([App\Http\Controllers\AdminBoatsController::class, 'store']) }}" method="POST" enctype="multipart/form-data">

        @csrf
        @method('POST')
        <!-- Name of the boat form group -->
        <div class="form-group">
            <label for="name">Name of The Boat:</label>
            <!-- Input for the name of the boat -->
            <input type="text" id="name" name="name" class="form-control" max="30"  aria-describedby="titleofthepost" pattern="/^[A-Za-z0-9 _]*[A-Za-z0-9][A-Za-z0-9 _]*$/"  placeholder="Titanic" required/>
            <!-- -->
            @if ( $errors->has('name') )
                @foreach( $errors->get('name') as $name_error )
                <div class="alert alert-danger" role="alert">
                    {{ $name_error }}
                </div>
                @endforeach
            @endif
        </div>
        <!-- -->

        <!-- Capacity of the boat form group -->
        <div class="form-group">
            <label for="limit">Capacity of the Boat:</label>
            <!-- Input field for the capacity -->
            <input class="form-control" name="limit" id="limit" type="number" max="20" min="1" pattern="/^[0-9]{1,2}$/" required/>
            <!-- -->
            @if ( $errors->has('limit') )
                @foreach( $errors->get('limit') as $body_error )
                <div class="alert alert-danger" role="alert">
                    {{ $body_error }}
                </div>
                @endforeach
            @endif
        </div>
        <!-- -->

        <!-- Photo for the boat form group -->
        <div class="form-group">
            <label for="file">Photo for the boat:</label>
            <!-- Inout field for the photo -->
            <input type="file" name="file" class="form-control-file" accept=".png, .jpg, .jpeg, .bmp" id="file">
            <!-- -->
            @if ( $errors->has('file') )
                @foreach( $errors->get('file') as $file_error )
                <div class="alert alert-danger" role="alert">
                    {{ $file_error }}
                </div>
                @endforeach
            @endif
        </div>
        <!-- -->

        <!-- Submit button for the form -->
        <div class="d-flex flex-row-reverse">
            <button type="submit" class="btnSubmit">
                <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                    <path fill="#4F8C6C" id="savehicon" d="M15,5V9H5V19H9.35C8.5,18.27 8,17.19 8,16A4,4 0 0,1 12,12A4,4 0 0,1 16,16C16,17.19 15.5,18.27 14.65,19H19V7.83L16.17,5H15M5,7H13V5H5V7M17,3L21,7V19A2,2 0 0,1 19,21H5C3.89,21 3,20.1 3,19V5A2,2 0 0,1 5,3H17M12,14A2,2 0 0,0 10,16A2,2 0 0,0 12,18A2,2 0 0,0 14,16A2,2 0 0,0 12,14Z" />
                </svg>
                Create
            </button>
        </div>
        <!-- -->

    </form>
    <!-- End of creating form -->
</div>
@endsection
