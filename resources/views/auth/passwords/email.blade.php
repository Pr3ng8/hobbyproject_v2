@extends('layouts.main')

@section('content')
<style>
.login-container{
    margin-top: 5%;
    margin-bottom: 5%;
}
.login-form{
    padding: 5%;
    box-shadow: 0 5px 8px 0 rgba(0, 0, 0, 0.2), 0 9px 26px 0 rgba(0, 0, 0, 0.19);
    background-image: linear-gradient(to bottom, #f77062 0%, #fe5196 100%);
}

.login-form h3{
    text-align: center;
    color: #333;
}

.login-container form{
    padding: 10%;
}

.btnSubmit {
    width: 50%;
    border: 2px solid white;
    border-radius: 2rem;
    padding: 1.5%;
    cursor: pointer;
    color: #fff;
}

.btnSubmit:hover,
.btnSubmit:focus { 
    border-color: #FCBC80;
    color: #FCBC80;
    text-decoration:none;
    color:#FCBC80;
}

.login-form .btnSubmit{
    font-weight: 600;
    background-color: transparent;
}
.login-form a{
    color: white;
    font-weight: 600;
    text-decoration: none;
}

.login-form a:hover,
.login-form a:focus {
    color:#FCBC80;
}
</style>
<div class="container login-container">
    <div class="row justify-content-md-center">
        <div class="col-md-6 login-form">

        <h1 class="display-4 text-white">Send Reset Password Email</h1>

            <form method="POST" action="{{ route('password.email') }}" aria-label="{{ __('Reset Password') }}">
                @csrf
                @method('POST')
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Your Email *" name="email" value="{{ old('email') }}" required autofocus autocomplete/>
                    @if ($errors->has('email'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btnSubmit">Send</button>
                </div>

            </form>

            <div class="form-group">
                <a href="{{ route('login') }}">{{ __('Back') }}</a>
            </div>
        </div>
    </div>
</div>
@endsection
