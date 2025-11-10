@extends('layouts.frontend.app')

@section('title')
  Edit {{ $room->name }}
@endsection

@section('content')
@if ($user->role=="manager")
<section class="EditRoomSection">

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

  <div class="container">
    <header class="header_Section">
      <h4 class="title_page">Edit-Room {{ $room->name }}</h4>
    </header>
    <div class="box_section">
      <form action="{{ route('room.update',$room->id) }}" method="POST" class="form">
        @csrf
        @method('put')
        <div class="form-group mb-3">
          <label for="roomName" class="form-label">Room Name</label>
          <input type="text" name="name" id="roomName" class="form-control" placeholder="Room Name" value="{{ $room->name }}">
        </div>
        <button class="btn btn-primary btnEditRoom">Edit</button>
      </form>
      <hr>
      <div class="room_employeesSection">
        <h3 class="tit_section">Staff</h3>
        <ul>
          @if ($room->employees->count() > 0)
            @foreach ($room->employees as $emp)
              <li class="emp_box">
                <p class="emp_name">{{ $emp->name }}</p>
                <form action="{{ route('employees.destroy',[$room->id,$emp->id]) }}" method="POST" class="delete-form">
                  @csrf
                  @method('DELETE')
                  <button type="button" class="btn btn-danger delete">Delete</button>
                </form>
              </li>
            @endforeach
          @else
            <h6 class="empty text-center mt-5">No Staff Yet..</h6>
          @endif

        </ul>
      </div>
    </div>
  </div>



</section>
@endif
