@extends('layouts.frontend.AuthApp')

@section('title')
  Login
@endsection
@section('content')
  <section class="login_section">
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
          <img src="{{ asset('uploads/logo_PNG_01.png') }}" alt="Web Logo" class="logo" />
          <p class="tit" trans="Welcome_Please_enter_your_details">Welcome! Please enter your details.</p>
        </header>
        <form class="Form_login" action="{{ route('auth.login') }}" method="POST">
          @csrf
          <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label" trans="Email">Email</label>
            <input type="email" class="form-control" id="exampleInputEmail1" placeholder="hi@example.com" name="email" value="{{ old('email') }}">
          </div>

          <div class="mb-3 form-group">
            <label for="loginInputPassword" class="form-label" trans="Password"> Password </label>
            <div class="box_input_password">
              <input type="password" class="form-control" id="loginInputPassword" name="password" placeholder="Password">
              <i class="fa-solid fa-eye eye_icon" id="togglePassword"></i>
            </div>
          </div>

          <div class="mb-3 form-check">
            <div class="box_check_form_input">
              <input type="checkbox" class="form-check-input" id="exampleCheck1" name="remember">
              <label class="form-check-label" for="exampleCheck1" trans="Remember_me">Remember me</label>
            </div>
            <a href="{{ route('forgetPassword') }}" class="forgot-password" trans="Forgot_Password">Forgot Password?</a>
          </div>

          <button type="submit" class="btn btn-primary" trans="Login">Login</button>
          {{-- <a href="" class="google-btn">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 48 48">
                  <path fill="#ffc107"
                      d="M43.611 20.083H42V20H24v8h11.303c-1.649 4.657-6.08 8-11.303 8c-6.627 0-12-5.373-12-12s5.373-12 12-12c3.059 0 5.842 1.154 7.961 3.039l5.657-5.657C34.046 6.053 29.268 4 24 4C12.955 4 4 12.955 4 24s8.955 20 20 20s20-8.955 20-20c0-1.341-.138-2.65-.389-3.917" />
                  <path fill="#ff3d00"
                      d="m6.306 14.691l6.571 4.819C14.655 15.108 18.961 12 24 12c3.059 0 5.842 1.154 7.961 3.039l5.657-5.657C34.046 6.053 29.268 4 24 4C16.318 4 9.656 8.337 6.306 14.691" />
                  <path fill="#4caf50"
                      d="M24 44c5.166 0 9.86-1.977 13.409-5.192l-6.19-5.238A11.9 11.9 0 0 1 24 36c-5.202 0-9.619-3.317-11.283-7.946l-6.522 5.025C9.505 39.556 16.227 44 24 44" />
                  <path fill="#1976d2"
                      d="M43.611 20.083H42V20H24v8h11.303a12.04 12.04 0 0 1-4.087 5.571l.003-.002l6.19 5.238C36.971 39.205 44 34 44 24c0-1.341-.138-2.65-.389-3.917" />
              </svg>
              <span trans="Continue_with_Google">Continue with Google</span>
          </a> --}}
        </form>
        <p class="switch_box"><span trans="Doesnt_have_an_account">Doesn't have an account?</span>
          <a href="{{ route('auth.registerView') }}" class="switch_link" trans="Sign_Up">Sign Up</a>
        </p>
        <div class="box_effect_icon">
          <img src="{{ asset('uploads/shining.png') }}" alt="" class="icon">
        </div>
      </div>
    </div>
  </section>



@endsection


