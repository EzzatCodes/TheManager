<?php

// channels.php
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('room.{roomId}', function ($user, $roomId) {
    $isMember  = $user->rooms()->where('rooms.id', $roomId)->exists();
    $isManager = $user->role === 'manager';
    return $isMember || $isManager; // المدير أو عضو بالغرفة
});

// Broadcast::channel('user.{userId}',function ($user, $userId)  {
//     $isMember  = $user->where('id', $userId)->exists();
//     $isManager = $user->role === 'manager';
//     return $isMember || $isManager; // المدير أو نفس المستخدم
// });


Broadcast::channel('user.{userId}', function ($user, $userId) {
    // يسمح لصاحب الحساب نفسه أو لأي مدير إنه يسمع القناة
    return (int) $user->id === (int) $userId
        || $user->role === 'manager';
});
