@extends('layouts.main')

@section('content')
<style>
.error-message {
  color: #E9F1DF;
}
.error-bg {
  background-color: #F23C50;
}
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

        <h1 class="display-4 text-white">Login</h1>

            <form  method="POST" action="{{ route('login') }}" aria-label="{{ __('Login') }}">
                @csrf
                @method('POST')
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Your Email *" name="email" value="{{ old('email') }}" required autofocus autocomplete/>
                    @if ($errors->has('email'))

                        @foreach ($errors->get('email') as $error)
                            <div class="col-12 error-bg my-1 mx-0 rounded">
                                <p class="error-message">{{ $error }}</p>
                            </div>
                        @endforeach

                    @endif
                </div>

                <div class="form-group">
                    <input type="password" class="form-control" placeholder="Your Password *"  name="password" required autocomplete/>
                    
                    @if ($errors->has('password'))

                        @foreach ($errors->get('password') as $error) .
                            <div class="col-12 error-bg my-1 mx-0 rounded">
                                <p class="error-message">{{ $error }}</p>
                            </div>
                        @endforeach

                    @endif

                </div>
                
                <div class="form-group">
                    <button type="submit" class="btnSubmit">Login</button>
                </div>

            </form>

            <div class="form-group">
                <a href="{{ route('password.request') }}">{{ __('Forgot Your Password?') }}</a>
            </div>
            <div class="form-group">
                <a href="{{ route('register') }}">{{ __('Register') }}</a>
            </div>
        </div>
    </div>
</div>
@endsection
