<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Room;
use Illuminate\Support\Str;
use App\Models\User;

class roomsController extends Controller
{


  public function create(Request $request)
  {
    if (Auth::check() && Auth::user()->role == "manager") {
      $request->validate([
        'name' => 'required|string|max:255',
      ]);
      $user = Auth::user();
      $room = Room::create([
        'name' => $request->name,
        'owner_id' => $user->id,
        'join_code' => Str::random(6),
      ]);

      return redirect()->back()->with('success', 'Room created successfully!');
    }
    abort(403, "Unauthorized");
  }

  public function edit(Room $room)
  {
    if (Auth::check() && Auth::user()->role == "manager") {
      if (Auth::id() !== $room->owner_id) {
        abort(403, "Unauthorized action.");
      }
      $user = Auth::user();
      return view('rooms.edit', compact("room", "user"));
    }
  }

  public function update(Request $request, Room $room)
  {
    if (Auth::check() && Auth::user()->role == "manager") {
      // $room = Room::findOrFail($id); // في حالة  id  مش Room $room
      $validated = $request->validate(['name' => 'required|string|max:255']);
      $room->update(
        ['name' => $request->name]
      );
      return redirect()->back()->with('success', 'Room Updated');
    }
  }

  public function delete(Room $room)
  {
    $room->delete();
    return redirect()->back()->with('success', 'Room Deleted Successfully');
  }


  public function join(Request $request)
  {
    if (Auth::check() && Auth::user()->role == "employee") {
      $user = Auth::user();
      if ($user->activation == 'online') {
        $data = $request->validate([
          'code' => "required|string|min:6",
        ]);
        $room = Room::where('join_code', $data['code'])->first();
        if (! $room) {
          return redirect()->back()->withErrors(['code' => 'Invalid join code!']);
        }
        if (! $room->employees()->where('user_id', $user->id)->exists()) {
          $room->employees()->attach($user->id);
        }
        return redirect()->back()->with('success', 'Joined Room Successfully');
      }else{
        return redirect()->back()->with('error','You must be online first!');
      }
    }
  }

  // delete the employee from database
  // public function deleteEmployee ($id) {
  //   $employee = User::findOrFail($id);
  //   $employee->delete();
  //   return redirect()->back()->with('success',"Employee Deleted Successfully");
  // }

  // delete the employee from room
  public function deleteEmployee(Room $room, User $user)
  {
    $room->employees()->detach($user->id);
    return redirect()->back()->with('success', "Employee Deleted Successfully");
  }



  public function indexSticky(Room $room)
  {
    $user = Auth::user();
    if ($user->role == "employee") {
      // update the room_user table to set the the_employee_room_opened_id to the current open room id
      /** @var \App\Models\User $user */
      $user->rooms()->where('user_id', $user->id)->update(['the_employee_room_opened_id' => null]);
      $user->rooms()->updateExistingPivot($room->id, ['the_employee_room_opened_id' => $room->id]);
    } elseif ($user->role == "manager") {
      // update the users table to set the The_rooms_manager_currently_open to the current open room id
      /** @var \App\Models\User $user */
      $openedRooms = $user->The_rooms_manager_currently_open ?? [];
      if (!is_array($openedRooms)) {
        $openedRooms = [];
      }
      if (!in_array($room->id, $openedRooms)) {
        $openedRooms[] = $room->id;
      }
      $user->update(['The_rooms_manager_currently_open' => $openedRooms]);
    }
    return view('rooms.sticky', compact("room", 'user'));
  }




  public function closeSticky(Room $room): void
  {
    $user = Auth::user();
    if ($user->role == "employee") {
      // update the room_user table to set the the_employee_room_opened_id to null
      /** @var \App\Models\User $user */
      $user->rooms()->where('user_id', $user->id)->update(['the_employee_room_opened_id' => null]);
    } elseif ($user->role == "manager") {
      // update the users table to remove the current open room id from The_rooms_manager_currently_open
      /** @var \App\Models\User $user */
      $openedRooms = $user->The_rooms_manager_currently_open ?? [];
      if (!is_array($openedRooms)) {
        $openedRooms = [];
      }
      $openedRooms = array_filter($openedRooms, function ($roomId) use ($room) {
        return $roomId !== $room->id;
      });
      $user->update(['The_rooms_manager_currently_open' => array_values($openedRooms)]);
    }
  }



  public function updateEmployee(User $employee)
  {
    if (Auth::check() && Auth::user() == $employee && $employee->role == "employee") {
      if ($employee->status == "free") {
        $employee->update([
          'status' => 'busy',
        ]);
        return redirect()->back();
      } elseif ($employee->status == "busy") {
        $employee->update([
          'status' => 'free',
        ]);
        return redirect()->back();
      } else {
        abort(404, "not found");
      }
    }
    abort(403, "Unauthorized");
  }
}
