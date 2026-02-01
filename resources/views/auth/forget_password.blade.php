@extends('layouts.frontend.AuthApp')

@section('title')
  Forget Password
@endsection
@section('content')
  <section class="forgetPasswordSection">
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
          {{-- <img src="#" alt="Logo" class="logo"/> --}}
        </header>
        <form class="Form_check_password" action="{{ route('forgot.send') }}" method="POST">
          @csrf
          <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label mt-4" trans="Email">Email</label>
            <input type="email" class="form-control" id="exampleInputEmail1" placeholder="hi@example.com" name="email" value="{{ old('email') }}">
          </div>
          <button type="submit" class="btn btn-primary" trans="check">check</button>
        </form>
        <div class="box_effect_icon">
          <img src="{{ asset('uploads/shining.png') }}" alt="" class="icon">
        </div>
      </div>
    </div>
  </section>
@endsection
