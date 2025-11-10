<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use App\Mail\ResetPasswordMail;

class authController extends Controller
{
  public function loginView()
  {
    return view('auth.login');
  }

  public function registerView()
  {
    return view('auth.register');
  }


  public function login(Request $request)
  {
    // if (Auth::check()) {
    //   $user = Auth::user();
    //   return redirect()->to(route("user.profile", $user->role));
    // }

    $request->validate([
      "email" => 'email|required',
      "password" => 'required'
    ]);

    $credentials = $request->only("email", "password");

    $remember = $request->has("remember");

    if (Auth::attempt($credentials, $remember)) {
      // $user = User::where('email',$credentials['email'])->first(); // دي طريقة
      $user = Auth::user(); // دي طريقة تانية
      /** @var \App\Models\User $user */
      $user->update([
        "status" => "free",
        "activation" => "online",
      ]);

      return redirect()->to(route('user.profile', $user->role))->with(["success" => "Login Successfully"]);
    }

    return redirect()->back()->withErrors([
      'email' => 'Invalid email or password.',
    ]);
  }


  public function register(Request $request)
  {
    $data = $request->validate([
      "name" => 'string|required',
      "role" => 'required|in:manager,employee',
      "email" => 'email|required|unique:users,email',
      "password" => 'required|min:6|confirmed',
    ]);

    $user = User::create([
      "name" => $data['name'],
      "email" => $data['email'],
      "password" => bcrypt($data['password']),
      "role" => $data['role'],
      "activation" => "online",
      "status" => "free",
    ]);

    Auth::login($user);

    return redirect()->to(route("user.profile", $user->role))->with([
      'success' => "Registration Successfully..",
      'user' => $user->makeHidden('password')
    ]);
  }



  public function closeApplication()
  {
    $user = Auth::user();
    if ($user->role == "employee") {
      // update the room_user table to set the the_employee_room_opened_id to null
      /** @var \App\Models\User $user */
      $user->rooms()->where('user_id', $user->id)->update(['the_employee_room_opened_id' => null]);
    } elseif ($user->role == "manager") {
      // update the users table make The_rooms_manager_currently_open = []
      /** @var \App\Models\User $user */
      $openedRooms = [];
      $user->update(['The_rooms_manager_currently_open' => array_values($openedRooms)]);
    }
    /** @var \App\Models\User $user */
    $user->update([
      'activation' => 'offline',
    ]);
    return redirect()->back();
  }

  public function openApplication()
  {
    if (Auth::check()) {
      $user = Auth::user();
      /** @var \App\Models\User $user */
      $user->update([
        'activation' => 'online',
      ]);
    } else {
      abort(403, "Unauthorized");
    }
    return redirect()->back();
  }






  public function logout()
  {
    $user = Auth::user(); // دي طريقة تانية
    /** @var \App\Models\User $user */
    $user->update([
      "activation" => "offline",
    ]);
    Auth::logout();

    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect()->route('auth.loginView')->with("success", "Logged Out Successfully");
  }



  public function forgetPassword()
  {
    return view('auth.forget_password');
  }




  public function sendLink(Request $request)
  {

    $validated = $request->validate([
      'email' => ['required', 'email'],
    ]);


    $user = User::firstWhere('email', $validated['email']);

    if (!$user) {
      return back()->with('status', 'If the email exists, a reset link will be sent.');
    }
    $rowToken = Str::random(64);
    $tokenHash = Hash::make($rowToken);
    $tokenHash = Hash::make($rowToken);

    // (2) امسح أي محاولات قديمة لنفس الإيميل (اختياري)
    DB::table('password_resets')->where('email', $user->email)->delete();

    DB::table('password_resets')->insert([
      'email' => $user->email,
      'token_hash' => $tokenHash,
      'created_at' => now(),
    ]);

    $resetUrl = route('password.reset.form', [
      'token' => $rowToken,
      'email' => $user->email,
    ]);

    Mail::to($user->email)->send(new ResetPasswordMail($user, $resetUrl));

    if (Mail::to($user->email)->send(new ResetPasswordMail($user, $resetUrl))) {
      return back()->with('success', 'We sent an email to your email address');
    } else {
      return back()->with('error', 'Something Wrong..');
    }
  }




  public function showResetForm(Request $request)
  {
    // نتوقع token + email في الـ query
    $request->validate([
      'token' => ['required', 'string'],
      'email' => ['required', 'email'],
    ]);

    return view('auth.reset_password_form', [
      'email' => $request->email,
      'token' => $request->token,
    ]);
  }


  public function reset(Request $request)
  {
    $data = $request->validate([
      'email' => ['required', 'email'],
      'token' => ['required', 'string'],
      'password' => ['required', 'min:8', 'confirmed'],
    ]);

    $record = DB::table('password_resets')->where("email", $data['email'])->orderByDesc('created_at')->first();
    if (!$record) {
      return back()->withErrors(['email' => 'Reset request not found.']);
    }

    $expired = Carbon::parse($record->created_at)->addMinute(60)->isPast();
    if ($expired) {
      return back()->withErrors(['token' => 'Token expired.']);
    }

    if (! Hash::check($data['token'], $record->token_hash)) {
      return back()->withErrors(['token' => 'Invalid token.']);
    }

    // كل شيء تمام: حدّث كلمة السر
    $user = User::firstWhere('email', $data['email']);
    if (! $user) {
      return back()->withErrors(['email' => 'User not found.']);
    }

    $user->forceFill([
      'password' => Hash::make($data['password']),
    ])->save();

    // نظّف السجلات (اختياري: امسح الطلبات كلها لهذا الإيميل)
    DB::table('password_resets')->where('email', $data['email'])->delete();

    // حوّله لصفحة تسجيل الدخول برسالة نجاح
    return redirect()->route('auth.loginView')->with('success', 'Password reset successfully. You can sign in now.');
  }
}
