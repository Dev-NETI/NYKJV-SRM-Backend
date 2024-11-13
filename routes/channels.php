<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::routes(['middleware' => ['api', 'auth:sanctum']]);

Broadcast::channel('chat.{chatId}', function ($user, $chatId) {
    return ['id' => $user->id, 'name' => $user->full_name];
});

Broadcast::channel('presence-chat', function ($user) {
    return $user;
});

