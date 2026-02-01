{{-- sticky.blade.php --}}
@extends('layouts.frontend.app')



<style>
  /* منطقة سحب فقط */
  .drag-area {
    -webkit-app-region: drag;
    cursor: move;
    height: 36px;
    width: 100%;
  }

  /* أي عنصر تفاعلي لازم يكون no-drag */
  button, a, input, select, textarea, label, .no-drag {
    -webkit-app-region: no-drag;
  }
</style>





@section('title')
  Room {{ $room->name }}
@endsection

@section('content')
{{-- <div class="drag-area"></div> --}}
  @if ($user->role == "manager")
    <section class="stickySection sectionManagerSticky">
      <div class="container">
        <div class="box_section">
          <div class="card">
            <div class="top">
              <h6 class="roomName">{{ $room->name }}</h6>
              <form action="{{ route('room.sticky.close',$room->id) }}" method="POST">
                @csrf
                <button id="closeStickyBtn" class="btn btn-danger closeStickyBtn no-drag" data-room-id="{{ $room->id }}">✕</button>
              </form>
            </div>
            <ul class="list staff_list" id="peers">
              @if ($room->employees->count() > 0)
                @foreach ($room->employees as $employee)
                  <li class="employee" id="emp-{{ $employee->id }}">
                    <p class="name info">{{ $employee->name }}</p>
                    <p class="status info employee_status @if ($employee->status == 'free') free @else busy @endif">
                      @if (in_array($employee->pivot->the_employee_room_opened_id , $user->The_rooms_manager_currently_open ?? []))
                        @if ($employee->activation == 'offline')
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
                <p class="empty text-center mt-5" id="empty_message">No Staff Yet..</p>
              @endif
            </ul>
          </div>
        </div>
      </div>
    </section>

  @elseif ($user->role == 'employee')
    <section class="stickySection sectionEmployeeSticky">
      <div class="container">
        <div class="box_section">
          <div class="card">
            <div class="top">
              <h6 class="roomName">{{ $room->name }}</h6>
              <form action="{{ route('room.sticky.close',$room->id) }}" method="POST">
                @csrf
                <button id="closeStickyBtn" class="btn btn-danger closeStickyBtn no-drag" data-room-id="{{ $room->id }}">✕</button>
              </form>
            </div>
            <form method="POST" action="{{ route('employee.updateStatus',$user->id) }}" class="FormStatus">
              @csrf
              @method('PUT')
              <button
                class="no-drag employeeStatusBtn btn @if ($user->status == 'free') btn-success btn-free @elseif ($user->status == 'busy') btn-danger btn-busy @endif">
                {{ $user->status }}
              </button>
            </form>
          </div>
        </div>
      </div>
    </section>
  @else
    <p>{{ abort(403,'Unauthorized') }}</p>
  @endif
@endsection


@push('PageStyleCss')
  <link rel="stylesheet" href="{{ asset('style/css/sticky.css') }}">
@endpush


@push('PageStyleJS')
  <script src="{{ asset('style/js/sticky.js') }}"></script>

  <script>
    // لازم يكون قبل أي سكربت بيستخدمه
    window.__ROOM_ID__ = {{ $room->id }};
    window.__MANAGER_OPEN_ROOMS__ = @json(Auth::user()->The_rooms_manager_currently_open);
    window.__EMPLOYEE_IDS__ = @json($room->employees->pluck('id'));
  </script>

  <script>
    // ==============
    // join realtime
    // ==============
    document.addEventListener('DOMContentLoaded', () => {
      const roomId = window.__ROOM_ID__;
      const list   = document.getElementById('peers');
      const managerOpenRooms = window.__MANAGER_OPEN_ROOMS__ ?? [];

      function whenReady(cb) {
        if (window.Echo) cb();
        else setTimeout(() => whenReady(cb), 50);
      }

      whenReady(() => {
        window.Echo.private(`room.${roomId}`)
          .listen('.employee.joined', (e) => {
            console.log('nice work', e);

            const empty = document.querySelector('.empty');
            if (empty) empty.remove();

            if (document.getElementById(`emp-${e.user_id}`)) return;

            let finalStatus;

            if (!managerOpenRooms.includes(e.opened_room)) {
              finalStatus = '<span class="offline">Offline</span>';
            } else {
              if (e.activation === 'offline') {
                finalStatus = '<span class="offline">offline</span>';
              } else {
                finalStatus = e.status;
              }
            }

            const li = document.createElement('li');
            li.className = 'employee';
            li.id = `emp-${e.user_id}`;
            li.innerHTML = `
              <p class="name info">${e.user_name}</p>
              <p class="status info ${e.status === 'free' ? 'free' : 'busy'}">${finalStatus}</p>
            `;
            list.appendChild(li);
          });
      });
    });

    // ================
    // remove realtime
    // ================
    document.addEventListener('DOMContentLoaded', () => {
      const roomId = window.__ROOM_ID__;
      const list   = document.getElementById('peers');
      const empty  = document.getElementById('empty_message');

      function whenReady(cb) {
        if (window.Echo) cb();
        else setTimeout(() => whenReady(cb), 50);
      }

      whenReady(() => {
        window.Echo.private(`room.${roomId}`)
          .listen('.employee.removed', (e) => {
            console.log(`Employee removed: ${e.user_name}`);

            const employee = document.getElementById(`emp-${e.user_id}`);
            if (employee) {
              employee.remove();

              if (list.children.length === 0 && empty) {
                list.innerHTML = empty.outerHTML;
              }
            } else {
              console.log(`Employee with ID ${e.user_id} not found in the list.`);
            }
          });
      });
    });

    // =====================
    // change status realtime
    // =====================
    document.addEventListener('DOMContentLoaded', () => {
      const roomId = window.__ROOM_ID__;
      const list   = document.getElementById('peers');

      function whenReady(cb) {
        if (window.Echo) cb();
        else setTimeout(() => whenReady(cb), 50);
      }

      whenReady(() => {
        window.Echo.private(`room.${roomId}`)
          .listen('.employee.status-changed', (e) => {
            console.log('status changed event:', e);

            const li = document.getElementById(`emp-${e.user_id}`);
            if (!li) return;

            const statusEl = li.querySelector('.status.info');
            if (!statusEl) return;

            statusEl.classList.remove('free', 'busy');

            let html;

            const openedRoom = e.opened_room;
            const activation = e.activation;
            const status     = e.status;
            const currentRoomId = Number(roomId);

            if (activation === 'offline' || openedRoom === null || Number(openedRoom) !== currentRoomId) {
              html = '<span class="offline">Offline</span>';
            } else {
              html = status;

              if (status === 'free') {
                statusEl.classList.add('free');
              } else if (status === 'busy') {
                statusEl.classList.add('busy');
              }
            }

            statusEl.innerHTML = html;
          });
      });
    });

    // =====================
    // activation change realtime
    // =====================


    document.addEventListener('DOMContentLoaded', () => {
      const employeeIds = window.__EMPLOYEE_IDS__ || [];
      const list        = document.getElementById('peers');

      function whenReady(cb) {
        if (window.Echo) cb();
        else setTimeout(() => whenReady(cb), 50);
      }

      whenReady(() => {
        employeeIds.forEach((userId) => {
          window.Echo.private(`user.${userId}`)
            .listen('.user.activation-changed', (e) => {
              console.log('activation event:', e);

              const li = document.getElementById(`emp-${e.user_id}`);
              if (!li) {
                console.log(`Employee with ID ${e.user_id} not found in the list.`);
                return;
              }

              const statusEl = li.querySelector('.status.info');
              if (!statusEl) return;

              // لو الموظف بقى offline → اعرض Offline وشيل free/busy
              if (e.activation === 'offline') {
                statusEl.classList.remove('free', 'busy');
                statusEl.innerHTML = '<span class="offline">Offline</span>';
              } else {
                // Online: مش هنغير حاجة هنا
                // حالات free/busy هتتحدث من Events تانية (join/status-changed)
              }
            });
        });
      });
    });



  </script>
@endpush
