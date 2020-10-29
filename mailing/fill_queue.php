<?php

require_once(__DIR__ . '/../bootstrap.php');

$message = 'Привіт, користувачу!
Деякий час у мене були технічні складності, але, сподіваюсь, ти мене не забув. За час вирішення моїх проблем я вдосконалився і відправлятиму тобі погоду у кращому форматі. 
Якщо виникнуть якісь питання — сміливо звертайся до @Kurusa

Щоби дізнатись погоду — напиши /start';
$user_list = \App\Models\User::where('is_blocked', 0)->get();
foreach ($user_list as $user) {
    \App\Models\UserQueue::create([
        'chat_id' => $user->chat_id,
        'message' => $message,
        'status' => 'NEW'
    ]);
}