<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

// Public dashboard channel - no authentication required for real-time stats
Broadcast::channel('dashboard', function () {
    return true;
});

// Device-specific channel - public for now, can add auth later
Broadcast::channel('device.{deviceId}', function ($user, $deviceId) {
    return true;
});

// User-specific notifications
Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
