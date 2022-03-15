@extends('layouts.main')

@section('content')
<style>

.block-button {
    width: 100%;
    border: 2px solid white;
    border-radius: 2rem;
    padding: 2.5%;
    padding-left: 20%;
    padding-right: 20%;
    cursor: pointer;
}

.block-button {  
  color: #FFFFF8;
  transition: 0.25s;
}

.block-button:hover,
.block-button:focus { 
    border-color: #FCBC80;
    color: #FCBC80;
    text-decoration:none;
}

hr {
    display: block;
    height: 1px;
    border: 0;
    border-top: 1px solid #FFFFF8;
    margin: 1em 0;
    padding: 0; 
}
@media screen and (max-width: 575px) {
    .btn-container :nth-child(2) {
        margin-top: 15%;
    }

    .btn-container :nth-child(1) a {

    }
    .btn-container div {
        margin-left: 0;
        margin-right: 0;
    }   
    .display-3 {
        font-size: 2.3rem;
    }
}
</style>
<!-- Page Content -->

<div class="container">

    <div class="row justify-content-center">

        <div class="col-12 text-center">

            <h1 class="display-3 mt-5 text-white">Welcome on the page!</h1>

            <div class="row my-5 justify-content-center btn-container">

                <div class="col-lg-4 col-md-6 col-sm-6">
                    <a class="block-button" href="{{ route('login') }}">Login</a>
                </div>

                <div class="col-lg-4 col-md-6 col-sm-6">
                    <a class="block-button" href="{{ route('register') }}">Register</a>
                </div>

            </div>
        </div>

    </div>

</div>
@endsection
