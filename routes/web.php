<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\roomsController;
use Illuminate\Broadcasting\Broadcasters\Broadcaster;
use Illuminate\Support\Facades\Broadcast;

Route::middleware('auth')->group(function (){
  Route::get('profile/{role}',[ProfileController::class,"profile"])->name("user.profile");
  Route::post("room/create",[roomsController::class, "create"])->name("room.create");
  Route::get("room/{room}/edit",[roomsController::class, "edit"])->name("room.edit");
  Route::Put("room/update/{room}",[roomsController::class, "update"])->name("room.update");
  Route::delete("room/delete/{room}",[roomsController::class, "delete"])->name("room.delete");
  Route::post("room/join",[roomsController::class, "join"])->name("room.join");
  Route::delete("employee/delete/{room}/{user}",[roomsController::class, "deleteEmployee"])->name("employees.destroy");
  Route::get('room/sticky/{room}',[roomsController::class, "indexSticky"])->name("room.stickyIndex");
  Route::Put('employee/updateStatus/{employee}',[roomsController::class, "updateEmployee"])->name("employee.updateStatus");

  Route::post('room/sticky/close/{room}',[roomsController::class, "closeSticky"])->name("room.sticky.close");


});
Broadcast::routes();
