@extends('layouts.frontend.app')

@section('title')
  Room {{ $room->name }}
@endsection
@section('content')
@if ($user->role == "manager")
  <section class="stickySection sectionManagerSticky">
    <div class="container">
      <div class="box_section">
        <div class="card">
          <div class="top">
            <h6 class="roomName">{{ $room->name }}</h6>
            <form action="{{ route('room.sticky.close',$room->id) }}" method="POST">
              @csrf
              <button id="closeStickyBtn" class="btn btn-danger closeStickyBtn" data-room-id="{{ $room->id }}">✕</button>
            </form>
          </div>
          <ul class="list staff_list" id="peers">
            @if ($room->employees->count() > 0)
              @foreach ( $room->employees as $employee )
              <li class="employee">
                <p class="name info">{{ $employee->name }}</p>
                <p class="status info @if ($employee->status == "free") free @else busy @endif">
                  @if(in_array($employee->pivot->the_employee_room_opened_id , $user->The_rooms_manager_currently_open ?? []))
                    @if ($employee->activation == "offline")
                      <span class="offline">{{ $employee->activation }}</span>
                      @else
                      {{ $employee->status }}
                    @endif
                  @else
                    <span class="offline">Offline</span>
                  @endif
                </p>
              </li>
              @endforeach
              @else
              <p class="empty text-center mt-5"> No Staff Yet..</p>
            @endif
          </ul>
        </div>
      </div>
    </div>
  </section>

@elseif($user->role == "employee")
  <section class="stickySection sectionEmployeeSticky">
    <div class="container">
      <div class="box_section">
        <div class="card">
          <div class="top">
            <h6 class="roomName">{{ $room->name }}</h6>
            <form action="{{ route('room.sticky.close',$room->id) }}" method="POST">
              @csrf
            <button id="closeStickyBtn" class="btn btn-danger closeStickyBtn" data-room-id="{{ $room->id }}">✕</button>
            </form>
          </div>
          <form method="POST" action="{{ route('employee.updateStatus',$user->id) }}" class="FormStatus">
            @csrf
            @method("PUT")
            <button
              class="employeeStatusBtn btn @if($user->status == 'free') btn-success btn-free @elseif($user->status == 'busy') btn-danger btn-busy @endif">
              {{ $user->status }}
            </button>
          </form>
        </div>
      </div>
    </div>
  </section>
@else
<P>{{ abort(403,"Unauthorized") }}</P>
@endif

@endsection


@push('PageStyleCss')
  <link rel="stylesheet" href="{{ asset('style/css/sticky.css') }}">
@endpush

@push('PageStyleJS')
  <script src="{{ asset('style/js/sticky.js') }}"></script>
@endpush


