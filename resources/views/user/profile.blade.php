@extends('layouts.frontend.app')

@section('title')
  Profile {{ $user->name }}
@endsection
@section('content')

<section class="profileSection">
  @if ($user->role=="manager")
    <section class="profileManagerSection">
      <header class="header_profile">
        <div class="container">
          <h4 class="title_page">Dashboard — Rooms</h4>
          <a href="{{ route('auth.logout') }}" class="btn btn-danger" id="logoutBtn"> Logout</a>
        </div>
      </header>

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
        <div class="box_section">
          <div class="toolbar">
            <span id="meInfo" class="user_info">{{ $user->name ." — ". $user->email }}</span>
            <div class="box_btns">
              <button id="createRoomBtn" class="btn btn-success">+ Create New Room</button>
              <button id="refreshBtn" class="btn btn btn-primary">⟳ Refresh</button>
            </div>
            <div id="loadingIndicator" class="loading-indicator" style="display:none;">
              <span class="spinner-small"></span>
              Loading...
            </div>
          </div>

          <form id="createForm" class="create_rooms" action="{{ route('room.create') }}" method="POST" >
            @csrf
            <div class="form-groub mb-3">
              <label class="form-label" style="color: white;">Room Name</label>
              <input id="roomName" type="text" class="form-control" name="name" placeholder="like: Support Team A" />
            </div>
            <div class="row box_btns g-2 ">
              <button id="submitRoom" class="btn btn-success col-3" type="submit">➕ Create Room</button>
              <button id="cancelCreate" class="btn btn btn-danger col-3" type="button">❌ Cancel</button>
            </div>
          </form>

          <hr class="separator">
          @if ($user->ManagedRooms->count() > 0)
            <div id="rooms" class="grid rooms_section">
              @foreach ($user->ManagedRooms as $room)
                <div class="room card p-2 mb-3 shadow-sm">
                  <header class="d-flex justify-content-between align-items-center mb-2 room-header">
                    <h5 class="mb-1 d-flex justify-content-between align-items-center">
                      <span>{{ $room->name }}</span>
                    </h5>
                      <div class="box_room_actions">
                        <a href="{{ route('room.edit',$room->id) }}"  class="btn btn-sm btn-primary Edit">Edit</a>
                        <form action="{{ route('room.delete', $room->id) }}" method="POST" style="display:inline;" class="delete-form">
                          @csrf
                          @method('DELETE')
                          <button type="button" class="btn btn-sm btn-danger delete">Delete</button>
                        </form>
                      </div>
                  </header>
                  <div class="m-2 info_room">
                    <small class="fst-italic">Number of employees: <b>{{ $room->employees()->count() }}</b></small><br>
                    <small>CreatedAt:{{ $room->created_at}} </small>
                  </div>
                  <div class="room-code-box row align-items-center g-2 mb-2">
                    <small class="col-6 join_code">Join Code: <b id="roomCode">{{ $room->join_code}}</b></small>
                    <button class="btn btn-sm btn-info copy col-6" data-code="{{ $room->join_code}}">Copy</button>
                  </div>

                  <!-- هنا زر يعطينا بيانات للـ sticky -->
                  <div class="d-flex justify-content-end mt-2">
                    <button  data-room-route="{{ route('room.stickyIndex',$room->id) }}" data-room-id="{{ $room->id }}" class="btn btn-success col-12 view-room" id="view_room_btn" >View Room</button>
                  </div>
                </div>
              @endforeach

            </div>
            @else
              <div id="emptyState" class="empty">
                There are no rooms yet. Click “Create New Room”.
              </div>
          @endif
        </div>
      </div>
    </section>
  @elseif($user->role=="employee")
    <section class="profileEmployeeSection" id="profileEmployeeSection">
      <header class="header_profile">
        <div class="container">
          <h4 class="title_page">Enter Code Room</h4>
          <a href="{{ route('auth.logout') }}" class="btn btn-danger" id="logoutBtn"> Logout</a>
        </div>
      </header>

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
        <div class="box_section">
          <div class="toolbar">
            <span id="meInfo" class="user_info">{{ $user->name ." — ". $user->email }}</span>
            <div class="box_btns">
              <button id="createRoomBtn" class="btn btn-success">Join Room</button>
              <button id="refreshBtn" class="btn btn btn-primary">⟳ Refresh</button>
            </div>
            <div id="loadingIndicator" class="loading-indicator" style="display:none;">
              <span class="spinner-small"></span>
              Loading...
            </div>
          </div>

          <form id="createForm" class="create_rooms Join_Form" action="{{ route('room.join') }}" method="POST" >
            @csrf
            <div class="form-groub mb-3">
              <label class="form-label" style="color: white;">Join Code:</label>
              <input id="roomCode" type="text" class="form-control" name="code" placeholder="like: 9K2F7B" />
            </div>
            <div class="row box_btns g-2 ">
              <button id="submitRoom" class="btn btn-success col-3" type="submit">Join</button>
              <button id="cancelCreate" class="btn btn btn-danger col-3" type="button">❌ Cancel</button>
            </div>
          </form>

          <hr class="separator">
          <h4 class="title_section">My Rooms</h4>
          <ul id="myRooms" class="list-group listEmployeesRoom">
            @if($user->rooms()->count() > 0)
              @foreach ($user->rooms as $room )
                <li class="list-group-item employee_room viewRoomEmployee" data-employee-room-route="{{ route('room.stickyIndex', $room->id) }}"   >
                  {{ $room->name }}
                </li>
              @endforeach
            @else
              <p class="empty">No Rooms Yet..</p>
            @endif
          </ul>
        </div>
      </div>
    </section>
  @endif
  @if ($user->role=="employee")
    <form action="{{ $user->activation == 'online' ? route('application.close') : route('application.open') }}" method="POST" >
      @csrf
      <button  class="activationBtn btn bnt-success {{ $user->activation == 'online' ? "online" : "offline" }}" id="activationBtn" data-user-status="{{ $user->activation }}">{{ $user->activation }}</button>
      <p class="activationAlert" id="activationAlert">You must be <span>online</span> first!</p>
    </form>
    <div class="backdrop" id="backdrop"></div>
  @endif
</section>

@endsection


@push('PageStyleJS')
  <script src="{{ asset('style/js/sticky.js') }}"></script>
@endpush





