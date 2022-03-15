@extends('layouts.main')

@section('content')
<style>
label {
  color: white;
}
.error-message {
  color: #E9F1DF;
}
.error-bg {
  background-color: #F23C50;
}
.reg-form{
    padding: 5%;
    box-shadow: 0 5px 8px 0 rgba(0, 0, 0, 0.2), 0 9px 26px 0 rgba(0, 0, 0, 0.19);
    background-image: linear-gradient(to bottom, #f77062 0%, #fe5196 100%);
}
.reg-form h3{
    text-align: center;
    color: #333;
}
.reg-container form{
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

.reg-form .btnSubmit{
    font-weight: 600;
    background-color: transparent;
}
.reg-form a{
    color: white;
    font-weight: 600;
    text-decoration: none;
}

.reg-form a:hover,
.reg-form a:focus {
    color:#FCBC80;
}
</style>

<div class="container reg-container">

  <div class="row justify-content-md-center">

    <div class="col-md-8 reg-form">

    <h1 class="display-4 text-white">Register</h1>

    <form class="" method="POST" action="{{ url('/register') }}" enctype="multipart/form-data">

      @csrf
      @method('POST')

      <div class="form-group">
        <label for="first_name">First Name</label>
        <input type="text" name="first_name" id="first_name" class="form-control" placeholder="First Name" value="{{ old('first_name') ?? '' }}" required>

        @if ($errors->has('first_name'))

          @foreach ($errors->get('first_name') as $error)
            <div class="col-12 error-bg my-1 mx-0 rounded">
              <p class="error-message">{{ $error }}</p>
            </div>
          @endforeach

        @endif

      </div>

      <div class="form-group">
        <label for="last_name">Last Name</label>
        <input type="text" name="last_name" id="last_name" class="form-control" placeholder="Last Name" value="{{ old('last_name') ?? '' }}" required>

        @if ($errors->has('last_name'))

          @foreach ($errors->get('last_name') as $error)
            <div class="col-12 error-bg my-1 mx-0 rounded">
              <p class="error-message">{{ $error }}</p>
            </div>
          @endforeach

        @endif

      </div>

      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" class="form-control" placeholder="Email" value="{{ old('email') ?? '' }}" autocomplete>

        @if ($errors->has('email'))

          @foreach ($errors->get('email') as $error)
            <div class="col-12 error-bg my-1 mx-0 rounded">
              <p class="error-message">{{ $error }}</p>
            </div>
          @endforeach

        @endif

      </div>

      <div class="form-group">
        <label for="birthdate">Date</label>
        <input type="text" name="birthdate" id="birthdate" class="form-control" placeholder="2000-01-01" value="{{ old('birthdate') ?? '' }}" pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}" required>

        @if ($errors->has('birthdate'))

          @foreach ($errors->get('birthdate') as $error)
            <div class="col-12 error-bg my-1 mx-0 rounded">
              <p class="error-message">{{ $error }}</p>
            </div>
          @endforeach

        @endif

      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" class="form-control" placeholder="*******" value="{{ old('password') ?? '' }}" required>

        @if ($errors->has('password'))

          @foreach ($errors->get('password') as $error)
            <div class="col-12 error-bg my-1 mx-0 rounded">
              <p class="error-message">{{ $error }}</p>
            </div>
          @endforeach

        @endif

      </div>

      <div class="form-group">
        <label for="password_confirmation">Password</label>
        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="*******" value="{{ old('password') ?? '' }}" required>

        @if ($errors->has('password_confirmation'))

          @foreach ($errors->get('password_confirmation') as $error)
            <div class="col-12 error-bg my-1 mx-0 rounded">
              <p class="error-message">{{ $error }}</p>
            </div>
          @endforeach

        @endif

      </div>

      <div class="d-flex flex-row-reverse">
        <button type="submit" class="btnSubmit">Register</button>
      </div>

    </form>


    </div>

  </div>

</div>
@endsection
