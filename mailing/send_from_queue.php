<?php

use App\Utils\Api;

require_once(__DIR__ . '/../bootstrap.php');
$bot = new Api(env('TELEGRAM_BOT_TOKEN'));

$message_list = \App\Models\UserQueue::where('status', 'NEW')->limit(150)->get();
if ($message_list) {
    foreach ($message_list as $message) {
        try {
            $response = $bot->sendMessage($message->chat_id, $message->message);
            \App\Models\UserQueue::where('id', $message->id)->update([
                'message_id' => $response->getMessageId(),
                'status' => 'SENT'
            ]);
        } catch (\TelegramBot\Api\Exception $e) {
            if ($e->getMessage() == 'Forbidden: bot was blocked by the user') {
                \App\Models\User::where('chat_id', $message->chat_id)->update([
                    'is_blocked' => 1
                ]);
            }
            \App\Models\UserQueue::where('id', $message->id)->update([
                'status' => 'ERROR',
                'message_id' => $e->getMessage(),
            ]);
        }
    }
}