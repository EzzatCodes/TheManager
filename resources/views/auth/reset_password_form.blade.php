@extends('layouts.frontend.AuthApp')

@section('title')
  Forget Password
@endsection
@section('content')
  <section class="ResetPasswordSection">
    <div class="container">
      @if ($errors->any())
        <div class="alert alert-danger">
          <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      @if (session('success'))
        <div class="session alert alert-success" id="session-alert">
          {{ session('success') }}
        </div>
      @elseif(session('error'))
        <div class="alert alert-danger">
          {{ session('error') }}
        </div>
      @endif
      <div class="box_section">
        <header class="header_section">
          <img src="{{ asset('uploads/logo_PNG_01.png') }}" alt="Web Logo" class="logo"/>
        </header>
        <form class="Form_check_password" action="{{ route('password.reset') }}" method="POST">
          @csrf
          <input type="hidden" name="token" value="{{ $token }}">
          <input type="hidden" name="email" value="{{ $email }}">
          <div class="mb-3 form-group">
            <label for="loginInputPassword" class="form-label" trans="Password"> Password </label>
            <div class="box_input_password">
              <input type="password" class="form-control" id="loginInputPassword" name="password" placeholder="Password">
              <i class="fa-solid fa-eye eye_icon" id="togglePassword"></i>
            </div>
          </div>

          <div class="mb-3 form-group">
            <label for="Confirm_Password" class="form-label" trans="Confirm_Password"> Confirm Password </label>
            <div class="box_input_password">
              <input type="password" class="form-control" id="Confirm_Password" name="password_confirmation"
                placeholder="Confirm Password">
              <i class="fa-solid fa-eye eye_icon" id="togglePassword"></i>
            </div>
          </div>

          <button type="submit" class="btn btn-primary" trans="Reset">Reset</button>
        </form>
        <div class="box_effect_icon">
          <img src="{{ asset('uploads/shining.png') }}" alt="" class="icon">
        </div>
      </div>
    </div>
  </section>
@endsection
