<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\facades\Auth;

class ProfileController extends Controller
{
  public function profile() {
    $user = Auth::user();
    return view("user.profile", compact('user'));
  }
}
