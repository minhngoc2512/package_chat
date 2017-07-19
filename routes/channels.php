<?php
/**
 * Created by PhpStorm.
 * User: minh
 * Date: 06/07/2017
 * Time: 10:55
 */
//Broadcast::channel('App.User.{id}', function ($user, $id) {
//    return (int) $user->id === (int) $id;
//});
//


Broadcast::channel('memberOnline.{id}', function ($user, $id) {
    return $user;
});
Broadcast::channel('memberOnline', function ($user) {
    return Auth::check();
});
Broadcast::channel('memberOnline.*', function ($user, $roomId) {
    if ($user->canJoinRoom($roomId)) {
        return ['id' => $user->id, 'name' => $user->name];
    }
});


Broadcast::channel('chat.{id}', function ($user, $id) {
    return $user;
});
Broadcast::channel('chat', function ($user) {
    return Auth::check();
});
Broadcast::channel('chat.*', function ($user, $roomId) {
    if ($user->canJoinRoom($roomId)) {
        return ['id' => $user->id, 'name' => $user->name];
    }
});
