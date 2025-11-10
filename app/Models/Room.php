<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Room extends Model
{
  use HasFactory;
  protected $fillable = [
    'name',
    'owner_id',
    'join_code',
  ];

  /* one to many  في الكود ده اقدر اجيب مدير كل غرفة  */
  public function owner()
  {
    return $this->belongsTo(User::class, 'owner_id');
  }

  public function employees()
  {
    return $this->belongsToMany(User::class, "room_user")->withPivot('the_employee_room_opened_id');;
  }
}

