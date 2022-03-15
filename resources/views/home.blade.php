@extends('layouts.main')

@section('content')
<link href="https://fonts.googleapis.com/css?family=Poiret+One" rel="stylesheet">
<style>

.paralellogram {
    -webkit-clip-path: polygon(0 0, 85% 0, 100% 100%, 0 100%);
    clip-path: polygon(0 0, 85% 0, 100% 100%, 0 100%);
    background-image: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    
}

.welcome {
    color: white;
    font-family: 'Poiret One', cursive;
    font-size: 50px;
}

.welcome-message {
    -webkit-border-radius: 222px;
    -webkit-border-top-left-radius: 0;
    -moz-border-radius: 222px;
    -moz-border-radius-topleft: 0;
    border-radius: 222px;
    border-top-left-radius: 0;
    background-color: #F1FFFF;
}

.user-name {
    text-align: left;
}

g {
    width: 80px;
    height: 100px;
}
@media only screen and (max-width : 768px) {
    .user-name {
        font-size: 25px;
        text-align: right;
    }
    .welcome {
        font-size: 25px;
    }
}

@media only screen and (max-width : 1024px) {
    .user-name {
        text-align: right;
    }
    .welcome, .user-name {
        font-size: 35px;
    }
}

</style>

<div class="container">

    <div class="row py-3 px-1 justify-content-center welcome-message">

        <div class="col-lg-3 col-md-5 col-sm-3 bg-warning welcome-label-box paralellogram">
            <div class="welcome">Welcome</div>
        </div>

        <div class="col-lg-4 col-md-5 col-sm-5 user-name-box">
            <div class="user-name display-4">{{ Auth::user()->getFullName() }}</div>
        </div>

    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 seperator">

        </div>
    </div>

</div>
@endsection
