<?php

use Illuminate\Support\Facades\Broadcast;

// Broadcast::channel('workbasket.{userId}', function ($user, $userId) {
//     \Log::info('Broadcast auth user:', ['user' => $user]);
// return true; 
//     return (int) $user->id === (int) $userId;
// });

Broadcast::channel('workbasket.frontliner', function ($user) {
    return true; 
});
Broadcast::channel('workbasket.contractor.{groupId}', function ($user) {
    return true; 
});
Broadcast::channel('workbasket.user.{userId}', function ($user) {
    return true; 
});