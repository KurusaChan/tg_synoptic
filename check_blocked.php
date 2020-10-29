<?php

use App\Utils\Api;

require_once(__DIR__ . '/bootstrap.php');
$bot = new Api(env('TELEGRAM_BOT_TOKEN'));

$user_list = \App\Models\User::where('is_blocked', 0)->get();
foreach ($user_list as $user) {
    try {
        $response = $bot->sendMessage(375036391, 'тест');
    } catch (\TelegramBot\Api\Exception $e) {
        if ($e->getMessage() == 'Forbidden: bot was blocked by the user') {
            \App\Models\User::where('chat_id', $user->chat_id)->update([
                'is_blocked' => 1
            ]);
        }
    }
}